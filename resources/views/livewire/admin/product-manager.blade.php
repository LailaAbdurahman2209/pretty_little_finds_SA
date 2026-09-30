<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Product Management') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search products..." class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-1/3">
                
                <button wire:click="openModal" type="button" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm text-sm" style="background-color: #4f46e5 !important; color: #ffffff !important; cursor: pointer !important;">
                    + Add New Product
                </button>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="h-10 w-10 rounded-lg object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center text-xs text-gray-500">No Image</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                R{{ number_format($product->price, 2) }}
                                @if($product->sale_price)
                                    <span class="text-xs text-red-500 line-through block">R{{ number_format($product->sale_price, 2) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button wire:click="edit({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button wire:click="delete({{ $product->id }})" wire:confirm="Are you sure you want to delete this product?" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Dark Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

            <!-- Modal Centering Container -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-2xl my-8">
                    <form wire:submit.prevent="store">
                        <!-- Pinned Header -->
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $productId ? 'Edit Product' : 'Create Product' }}</h3>
                            <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                        </div>
                        
                        <!-- Scrollable Inputs Area -->
                        <div class="p-6 max-h-[60vh] overflow-y-auto">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-2 col-span-2 sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Product Name</label>
                                    <input type="text" wire:model.live="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-2 col-span-2 sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Category</label>
                                    <select wire:model="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-2 col-span-2 sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                                    <input type="text" wire:model="slug" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50">
                                    @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-2 col-span-2 sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                                    <input type="number" wire:model="stock" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-2 col-span-2 sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Regular Price (ZAR)</label>
                                    <input type="text" wire:model="price" placeholder="0.00" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-2 col-span-2 sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Sale Price (Optional)</label>
                                    <input type="text" wire:model="sale_price" placeholder="0.00" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('sale_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-2 col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea wire:model="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                </div>

                                <div class="mb-2 col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Product Image</label>
                                    <input type="file" wire:model="image" class="mt-1 block w-full text-sm">
                                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-2 flex items-center space-x-6 col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="is_featured" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Featured</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Pinned Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-2">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-md text-sm font-medium">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>