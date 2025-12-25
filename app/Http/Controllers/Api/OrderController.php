<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Notification;

class OrderController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $query = $request->user()->orders()->with(['items', 'address']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('order_status', $request->status);
            }

            // Filter by date range
            if ($request->has('startDate')) {
                $query->whereDate('created_at', '>=', $request->startDate);
            }
            if ($request->has('endDate')) {
                $query->whereDate('created_at', '<=', $request->endDate);
            }

            $orders = $query->orderBy('created_at', 'desc')
                ->paginate($request->input('limit', 10));

            return $this->paginatedResponse($orders, 'Orders retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve orders: ' . $e->getMessage(), [], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $order = $request->user()->orders()
                ->with(['items.product', 'address'])
                ->findOrFail($id);

            return $this->successResponse($order, 'Order retrieved successfully');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Order not found');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'address_id' => 'required|exists:addresses,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'payment_method' => 'required|string|in:COD,Bank Transfer,E-wallet,Credit Card',
                'notes' => 'nullable|string',
                'delivery_fee' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
            ]);

            DB::beginTransaction();

            try {
                $subtotal = 0;
                $orderItems = [];

                // Validate and prepare order items
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    if (!$product->is_available || $product->stock < $item['quantity']) {
                        throw new \Exception("Product {$product->name} is not available or insufficient stock");
                    }

                    $price = $product->price;
                    $itemSubtotal = $price * $item['quantity'];
                    $subtotal += $itemSubtotal;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'price' => $price,
                        'subtotal' => $itemSubtotal,
                    ];

                    // Update product stock
                    $product->decrement('stock', $item['quantity']);
                }

                $deliveryFee = $validated['delivery_fee'] ?? 0;
                $discount = $validated['discount'] ?? 0;
                $totalAmount = $subtotal + $deliveryFee - $discount;

                // Create order
                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'order_number' => Order::generateOrderNumber(),
                    'address_id' => $validated['address_id'],
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'discount' => $discount,
                    'total_amount' => $totalAmount,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'Pending',
                    'order_status' => 'Pending',
                    'notes' => $validated['notes'] ?? null,
                ]);

                // Create order items
                foreach ($orderItems as $item) {
                    $order->items()->create($item);
                }

                // Clear cart
                $cart = $request->user()->cart;
                if ($cart) {
                    $cart->items()->delete();
                    $cart->updateTotal();
                }

                // Create Notification
                $itemsList = collect($orderItems)->map(function ($item) {
                    return $item['product_name'] . ' (' . $item['quantity'] . 'x)';
                })->implode(', ');

                Notification::create([
                    'user_id' => $request->user()->id,
                    'title' => 'Order Placed Successfully',
                    'message' => 'Your order #' . $order->order_number . ' has been placed successfully. Items: ' . $itemsList,
                    'type' => 'order_placed',
                    'is_read' => false,
                ]);

                DB::commit();

                $order->load(['items', 'address']);

                return $this->createdResponse($order, 'Order created successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create order: ' . $e->getMessage(), [], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        try {
            $order = $request->user()->orders()->findOrFail($id);

            if (!in_array($order->order_status, ['Pending', 'Processing'])) {
                return $this->errorResponse('Cannot cancel order with status: ' . $order->order_status, [], 400);
            }

            // Restore product stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $order->update(['order_status' => 'Cancelled']);

            return $this->successResponse($order, 'Order cancelled successfully');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Order not found');
        }
    }

    public function track(Request $request, $id)
    {
        try {
            $order = $request->user()->orders()->findOrFail($id);

            $trackingInfo = [
                'order_number' => $order->order_number,
                'status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ];

            return $this->successResponse($trackingInfo, 'Order tracking information retrieved');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Order not found');
        }
    }
}
