@extends('layouts.admin')

@section('header', 'Orders Management')

@section('content')
<div class="bg-white shadow-sm rounded-xl border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">All Checkouts</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $order->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="max-w-xs truncate">
                                @foreach($order->items as $item)
                                    {{ $item->quantity }}x {{ $item->product_name }}{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-semibold">
                            IDR {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->order_status === 'Delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->order_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->order_status === 'Processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->order_status === 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $order->order_status === 'Shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                            ">
                                {{ $order->order_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center uppercase tracking-widest py-10">
                            Belum ada yang melakukan check out.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
</div>
@endsection
