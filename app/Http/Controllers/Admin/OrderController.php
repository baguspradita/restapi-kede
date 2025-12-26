<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product', 'address'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'address']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
            'payment_status' => 'required|in:Pending,Paid,Failed,Refunded',
        ]);

        $order->update([
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Order status updated successfully');
    }
}
