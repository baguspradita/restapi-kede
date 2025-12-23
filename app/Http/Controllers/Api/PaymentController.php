<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    use ApiResponse;

    public function process(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'payment_method' => 'required|string|in:COD,Bank Transfer,E-wallet',
            ]);

            $order = $request->user()->orders()->findOrFail($validated['order_id']);

            if ($order->payment_status !== 'Pending') {
                return $this->errorResponse('Payment already processed', [], 400);
            }

            // Simulate payment processing
            // In real implementation, integrate with payment gateway here

            if ($validated['payment_method'] === 'COD') {
                // COD doesn't need immediate payment
                $order->update([
                    'payment_method' => $validated['payment_method'],
                    'order_status' => 'Processing',
                ]);

                return $this->successResponse([
                    'order' => $order,
                    'message' => 'Order confirmed. Payment will be collected on delivery.',
                ], 'Payment method set successfully');
            }

            // For other payment methods, mark as paid
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
        // Handle payment gateway webhooks
        // This is a placeholder for actual payment gateway integration

        try {
            // Validate webhook signature
            // Process webhook data
            // Update order status

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
