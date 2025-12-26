@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Stat Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $productCount }}</dd>
                    </dl>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 mt-6 border-t border-gray-100">
                <div class="text-sm">
                    <a href="{{ route('admin.products.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">View all products <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $orderCount }}</dd>
                    </dl>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 mt-6 border-t border-gray-100">
                <div class="text-sm">
                    <a href="{{ route('admin.orders.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">View all orders <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Customers Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Customers</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $userCount }}</dd>
                    </dl>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 mt-6 border-t border-gray-100">
                <div class="text-sm">
                    <a href="{{ route('admin.users.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">View users <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Checkouts</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</td>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No recent orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
