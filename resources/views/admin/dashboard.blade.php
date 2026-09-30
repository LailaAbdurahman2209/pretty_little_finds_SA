mkdir -p resources/views/admin

cat << 'EOF' > resources/views/admin/dashboard.blade.php
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Products</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_products'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Categories</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_categories'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Orders</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Pending Orders</div>
                    <div class="mt-2 text-3xl font-bold text-amber-600">{{ $stats['pending_orders'] }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick Management Links</h3>
                <p class="text-gray-600 text-sm">Welcome to the Pretty Little Finds store management overview.</p>
            </div>
        </div>
    </div>
</x-app-layout>
EOF