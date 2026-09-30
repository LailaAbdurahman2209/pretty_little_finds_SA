<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
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

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
            ->when($this->sortBy === 'price_low', fn($q) => $q->orderBy('price', 'asc'))
            ->when($this->sortBy === 'price_high', fn($q) => $q->orderBy('price', 'desc'))
            ->when($this->sortBy === 'newest', fn($q) => $q->latest())
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();

        return view('livewire.shop', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}