<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Order;

class IpaymuService
{
    public function createPayment(Order $order)
    {
        $config = config('ipaymu');
        $baseUrl = $config['sandbox'] ? $config['sandbox_base_url'] : $config['live_base_url'];
        $url = rtrim($baseUrl, '/') . '/payment';
        $va = $config['va'];
        $apiKey = $config['api_key'];
        $method = 'POST';

        // iPaymu v2 requires timestamp in YYYYMMDDhhmmss format
        $timestamp = now()->format('YmdHis');

        $products = $order->items()->get()->map(function ($item) {
            return [
                'name' => $item->product_name,
                'qty' => $item->quantity,
                'price' => (float) $item->price,
            ];
        });

        $referenceId = $order->order_number;

        // Build request body according to iPaymu documentation
        $body = [
            'product' => $products->pluck('name')->toArray(),
            'qty' => $products->pluck('qty')->toArray(),
            'price' => $products->pluck('price')->toArray(),
            'returnUrl' => $config['return_url'] . '?reference_id=' . urlencode($referenceId),
            'cancelUrl' => $config['cancel_url'] . '?reference_id=' . urlencode($referenceId),
            'notifyUrl' => $config['notify_url'],
            'buyerName' => $order->user->name ?? 'Customer',
            'buyerEmail' => $order->user->email ?? 'customer@example.com',
            'buyerPhone' => $order->user->phone ?? '0800000000',
            'referenceId' => $referenceId,
            'expired' => 2, // 2 hours expiry
        ];

        // Generate signature as per iPaymu documentation
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'va' => $va,
            'signature' => $signature,
            'timestamp' => $timestamp,
        ])->post($url, $body);

        // Get raw response
        $result = $response->json();

        // Check if request failed
        if (!$response->successful() || !isset($result['Status']) || $result['Status'] != 200) {
            $errorMsg = $result['Message'] ?? $result['message'] ?? 'Failed to create payment';
            throw new \Exception('iPaymu Error: ' . $errorMsg . ' | Response: ' . json_encode($result));
        }

        // Extract payment data
        $data = $result['Data'] ?? null;
        if (!$data || empty($data['Url'])) {
            throw new \Exception('Invalid payment response: Missing payment URL');
        }

        return [
            'redirect_url' => $data['Url'],
            'session_id' => $data['SessionID'] ?? null,
            'reference_id' => $body['referenceId'],
        ];
    }
}
