<?php

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedCategory = '';
    public string $sortBy = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingSelectedCategory(): void { $this->resetPage(); }
    public function updatingSortBy(): void { $this->resetPage(); }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);

        if (!$product || $product->stock_quantity <= 0 || !$product->is_active) {
            session()->flash('error', 'This product is currently out of stock.');
            return;
        }

        $this->dispatch('cart-updated', productId: $productId);
        session()->flash('message', "{$product->name} added to cart!");
    }

    public function with(): array
    {
        return [
            'products' => Product::query()
                ->where('is_active', true)
                ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
                ->when($this->sortBy === 'price_low', fn($q) => $q->orderBy('price', 'asc'))
                ->when($this->sortBy === 'price_high', fn($q) => $q->orderBy('price', 'desc'))
                ->when($this->sortBy === 'newest', fn($q) => $q->latest())
                ->paginate(12),
            'categories' => Category::where('is_active', true)->get(),
        ];
    }
}; ?>

<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Banner -->
            <div class="mb-8 text-center sm:text-left flex flex-col md:flex-row md:items-center md:justify-between border-b pb-6 border-gray-200">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Our Catalogue</h1>
                    <p class="mt-2 text-sm text-gray-600">Browse our boutique selection of premium items.</p>
                </div>
                
                @if (session()->has('message'))
                    <div class="mt-4 md:mt-0 p-3 bg-green-100 border border-green-400 text-green-800 text-sm rounded-md flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('message') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mt-4 md:mt-0 p-3 bg-red-100 border border-red-400 text-red-800 text-sm rounded-md">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Filter Bar -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="relative">
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Search products..." 
                        class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div>
                    <select 
                        wire:model.live="selectedCategory" 
                        class="w-full py-2 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select 
                        wire:model.live="sortBy" 
                        class="w-full py-2 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    >
                        <option value="newest">Newest Arrivals</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-100 flex flex-col justify-between overflow-hidden group">
                        <div>
                            <div class="block relative aspect-square bg-gray-100 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                @endif

                                @if($product->stock_quantity <= 0)
                                    <div class="absolute top-2 right-2 bg-gray-900 bg-opacity-80 text-white text-xs font-bold px-2 py-1 rounded">
                                        OUT OF STOCK
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <span class="text-xs text-indigo-600 font-medium uppercase tracking-wider">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                                <h2 class="mt-1 text-base font-semibold text-gray-900 truncate">
                                    {{ $product->name }}
                                </h2>
                                
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">R{{ number_format($product->price, 2) }}</span>
                                    
                                    @if($product->stock_quantity > 0)
                                        <span class="text-xs font-semibold text-emerald-600 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            In Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="p-4 pt-0">
                            @if($product->stock_quantity > 0)
                                <button 
                                    wire:click="addToCart({{ $product->id }})" 
                                    wire:loading.attr="disabled"
                                    class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-lg transition-colors shadow-sm flex items-center justify-center space-x-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                    <span>ADD TO CART</span>
                                </button>
                            @else
                                <button disabled class="w-full py-2.5 px-4 bg-gray-200 text-gray-500 font-medium text-sm rounded-lg cursor-not-allowed">
                                    OUT OF STOCK
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-xl border border-gray-100">
                        <p class="text-gray-500 text-base">No products match your search or filter criteria.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>