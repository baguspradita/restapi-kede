<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ApiResponse;
use App\Services\IpaymuService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    use ApiResponse;

    protected IpaymuService $ipaymuService;

    public function __construct(IpaymuService $ipaymuService)
    {
        $this->ipaymuService = $ipaymuService;
    }

    public function process(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'payment_method' => 'required|string|in:COD,IPAYMU,Bank Transfer,E-wallet',
            ]);

            $order = $request->user()->orders()->findOrFail($validated['order_id']);

            if ($order->payment_status !== 'Pending') {
                return $this->errorResponse('Payment already processed', [], 400);
            }

            if ($validated['payment_method'] === 'COD') {
                $order->update([
                    'payment_method' => $validated['payment_method'],
                    'order_status' => 'Processing',
                ]);

                return $this->successResponse([
                    'order' => $order,
                    'message' => 'Order confirmed. Payment will be collected on delivery.',
                ], 'Payment method set successfully');
            }

            if ($validated['payment_method'] === 'IPAYMU') {
                try {
                    $payment = $this->ipaymuService->createPayment($order);

                    $order->update([
                        'payment_method' => 'IPAYMU',
                        'payment_status' => 'Pending',
                        'order_status' => 'Pending',
                    ]);

                    return $this->successResponse([
                        'order' => $order,
                        'redirect_url' => $payment['redirect_url'],
                        'reference_id' => $payment['reference_id'],
                        'session_id' => $payment['session_id'],
                    ], 'Payment link generated');
                } catch (\Exception $e) {
                    \Log::error('iPaymu payment creation failed', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                    return $this->errorResponse(
                        'Failed to create payment link: ' . $e->getMessage(),
                        [],
                        500
                    );
                }
            }

            // fallback
            $order->update([
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'Paid',
                'order_status' => 'Processing',
            ]);

            return $this->successResponse([
                'order' => $order,
                'transaction_id' => 'TXN-' . time(),
            ], 'Payment processed successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Payment processing failed: ' . $e->getMessage(), [], 500);
        }
    }

    public function status(Request $request, $id)
    {
        try {
            $order = $request->user()->orders()->findOrFail($id);

            return $this->successResponse([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'total_amount' => $order->total_amount,
            ], 'Payment status retrieved successfully');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Order not found');
        }
    }

    public function webhook(Request $request)
    {
        try {
            $referenceId = $request->input('reference_id')
                ?? $request->input('ReferenceId')
                ?? $request->input('ref_id');

            // iPaymu callback fields:
            // Status / StatusCode / ResponseCode
            $statusRaw = $request->input('status') ?? $request->input('Status');
            $statusCode = $request->input('StatusCode') ?? $request->input('status_code');
            $responseCode = $request->input('ResponseCode') ?? $request->input('response_code');
            $status = strtolower((string) $statusRaw);

            if (!$referenceId) {
                return response()->json(['status' => 'error', 'message' => 'Missing reference_id'], 400);
            }

            $order = Order::where('order_number', $referenceId)->first();
            if (!$order) {
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Never downgrade a confirmed payment.
            if ($order->payment_status === 'Paid') {
                return response()->json(['status' => 'success']);
            }

            $statusCodeStr = (string) $statusCode;
            $responseCodeStr = (string) $responseCode;

            $isSuccess = false;
            if (in_array($status, ['berhasil', 'success', 'paid'], true)) {
                $isSuccess = true;
            }
            if (in_array($statusCodeStr, ['1', '00'], true) || in_array($responseCodeStr, ['00', '000'], true)) {
                $isSuccess = true;
            }

            // Only mark FAILED if it's clearly a failure/cancel.
            $isFailure = false;
            if (in_array($status, ['gagal', 'failed', 'cancel', 'cancelled', 'batal'], true)) {
                $isFailure = true;
            }
            if (in_array($statusCodeStr, ['0', '99'], true) || in_array($responseCodeStr, ['99'], true)) {
                $isFailure = true;
            }

            if ($isSuccess) {
                $order->update([
                    'payment_status' => 'Paid',
                    'order_status' => 'Processing',
                ]);
            } elseif ($isFailure) {
                $order->update([
                    'payment_status' => 'Failed',
                    'order_status' => 'Pending',
                ]);
            } else {
                // Unknown/intermediate updates should keep it Pending, not Failed.
                $order->update([
                    'payment_status' => 'Pending',
                    'order_status' => 'Pending',
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function returnHandler(Request $request)
    {
        try {
            $referenceId = $request->input('reference_id');
            if (!$referenceId) {
                return response()->view('payments.return', [
                    'success' => false,
                    'message' => 'Missing reference ID.',
                    'orderNumber' => null,
                    'customer' => null,
                    'total' => null,
                ], 400);
            }

            $order = Order::where('order_number', $referenceId)->with('user')->first();
            if (!$order) {
                return response()->view('payments.return', [
                    'success' => false,
                    'message' => 'Order not found.',
                    'orderNumber' => $referenceId,
                    'customer' => null,
                    'total' => null,
                ], 404);
            }

            return response()->view('payments.return', [
                'success' => $order->payment_status === 'Paid',
                'message' => $order->payment_status === 'Paid'
                    ? 'Payment confirmed. You can safely close this page.'
                    : 'Waiting for confirmation. If you already paid, this will update shortly.',
                'orderNumber' => $order->order_number,
                'customer' => optional($order->user)->name,
                'total' => $order->total_amount,
            ]);

        } catch (\Exception $e) {
            return response()->view('payments.return', [
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
                'orderNumber' => null,
                'customer' => null,
                'total' => null,
            ], 500);
        }
    }

    public function cancelHandler(Request $request)
    {
        try {
            $referenceId = $request->input('reference_id');

            if (!$referenceId) {
                return response()->view('payments.cancel', [
                    'success' => false,
                    'message' => 'Missing reference ID.',
                    'orderNumber' => null,
                    'customer' => null,
                    'total' => null,
                ], 400);
            }

            $order = Order::where('order_number', $referenceId)->with('user')->first();
            if (!$order) {
                return response()->view('payments.cancel', [
                    'success' => false,
                    'message' => 'Order not found.',
                    'orderNumber' => $referenceId,
                    'customer' => null,
                    'total' => null,
                ], 404);
            }

            return response()->view('payments.cancel', [
                'success' => false,
                'message' => 'You cancelled the payment process. Your order is still pending and you can try paying again.',
                'orderNumber' => $order->order_number,
                'customer' => optional($order->user)->name,
                'total' => $order->total_amount,
            ]);

        } catch (\Exception $e) {
            return response()->view('payments.cancel', [
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
                'orderNumber' => null,
                'customer' => null,
                'total' => null,
            ], 500);
        }
    }
}
