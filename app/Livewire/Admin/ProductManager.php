<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isModalOpen = false;

    public $productId = null;
    public $category_id = '';
    public $name = '';
    public $slug = '';
    public $description = '';
    public $price = '';
    public $sale_price = '';
    public $stock = 0;
    public $image;
    public $existingImage = null;
    public $is_active = true;
    public $is_featured = false;

    protected function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $this->productId,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function render()
    {
        $products = Product::with('category')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $categories = Category::where('is_active', true)->get();

        return view('livewire.admin.product-manager', compact('products', 'categories'))
            ->layout('layouts.app');
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->productId = null;
        $this->category_id = '';
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->price = '';
        $this->sale_price = '';
        $this->stock = 0;
        $this->image = null;
        $this->existingImage = null;
        $this->is_active = true;
        $this->is_featured = false;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        $imagePath = $this->existingImage;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        Product::updateOrCreate(
            ['id' => $this->productId],
            [
                'category_id' => $this->category_id,
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'price' => $this->price,
                'sale_price' => $this->sale_price ?: null,
                'stock' => $this->stock,
                'image' => $imagePath,
                'is_active' => $this->is_active,
                'is_featured' => $this->is_featured,
            ]
        );

        session()->flash('message', $this->productId ? 'Product updated successfully.' : 'Product created successfully.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->category_id = $product->category_id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->sale_price = $product->sale_price;
        $this->stock = $product->stock;
        $this->existingImage = $product->image;
        $this->is_active = (bool) $product->is_active;
        $this->is_featured = (bool) $product->is_featured;

        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Product deleted successfully.');
    }
}