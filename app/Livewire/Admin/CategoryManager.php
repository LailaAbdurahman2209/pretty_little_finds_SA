<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isModalOpen = false;

    public $categoryId = null;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $image;
    public $existingImage = null;
    public $is_active = true;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $this->categoryId,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ];
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function render()
    {
        $categories = Category::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.category-manager', compact('categories'))
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
        $this->categoryId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->image = null;
        $this->existingImage = null;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        $imagePath = $this->existingImage;
        if ($this->image) {
            $imagePath = $this->image->store('categories', 'public');
        }

        Category::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'image' => $imagePath,
                'is_active' => $this->is_active,
            ]
        );

        session()->flash('message', $this->categoryId ? 'Category updated successfully.' : 'Category created successfully.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->existingImage = $category->image;
        $this->is_active = (bool) $category->is_active;

        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully.');
    }
}