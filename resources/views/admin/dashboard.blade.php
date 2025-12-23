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

    <!-- Placeholder Card 1 -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Sales</dt>
                        <dd class="text-3xl font-semibold text-gray-900">-</dd>
                    </dl>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 mt-6 border-t border-gray-100">
                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">View details <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Placeholder Card 2 -->
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
@endsection
