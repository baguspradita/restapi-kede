@extends('layouts.admin')

@section('header', 'Order Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Orders
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Summary & Items -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                    {{ $order->order_status === 'Delivered' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->order_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->order_status === 'Processing' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $order->order_status === 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $order->order_status === 'Shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                ">
                    {{ $order->order_status }}
                </span>
            </div>
            
            <div class="p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-0 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-0 py-4">
                                <div class="flex items-center">
                                    <div class="ml-0">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $item->product->category->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-500">IDR {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">IDR {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-500">Subtotal</td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">IDR {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-500">Delivery Fee</td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">IDR {{ number_format($order->delivery_fee, 0, ',', '.') }}</td>
                        </tr>
                        @if($order->discount > 0)
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-red-500">Discount</td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-red-600">-IDR {{ number_format($order->discount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="bg-indigo-50">
                            <td colspan="3" class="px-6 py-4 text-right text-base font-bold text-indigo-900">Total Amount</td>
                            <td class="px-6 py-4 text-right text-lg font-bold text-indigo-700">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Order Status</h3>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                    <select name="order_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Pending" {{ $order->order_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Processing" {{ $order->order_status === 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Shipped" {{ $order->order_status === 'Shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="Delivered" {{ $order->order_status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Cancelled" {{ $order->order_status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                    <select name="payment_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Pending" {{ $order->payment_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Paid" {{ $order->payment_status === 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Failed" {{ $order->payment_status === 'Failed' ? 'selected' : '' }}>Failed</option>
                        <option value="Refunded" {{ $order->payment_status === 'Refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">Update Status</button>
            </form>
        </div>
    </div>

    <!-- Customer & Shipping Info -->
    <div class="space-y-6">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Details</h3>
            <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                    {{ substr($order->user->name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                    <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                </div>
            </div>
            <div class="space-y-2 border-t border-gray-100 pt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Payment Method</span>
                    <span class="font-medium text-gray-900">{{ $order->payment_method }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Payment Status</span>
                    <span class="font-medium text-gray-900">{{ $order->payment_status }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
            @if($order->address)
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-semibold text-gray-900">{{ $order->address->label }}</p>
                    <p>{{ $order->address->recipient_name }}</p>
                    <p>{{ $order->address->full_address }}</p>
                    <p>{{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postal_code }}</p>
                    <p>{{ $order->address->phone }}</p>
                </div>
            @else
                <p class="text-sm text-gray-500">No address information available.</p>
            @endif
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
            <p class="text-sm text-gray-600 italic">
                {{ $order->notes ?? 'No notes provided for this order.' }}
            </p>
        </div>
    </div>
</div>
@endsection
