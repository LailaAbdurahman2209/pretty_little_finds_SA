<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryAndProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::create([
            'name' => 'Gifts & Accessories',
            'slug' => 'gifts-and-accessories',
            'description' => 'Handpicked special finds and hampers.',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Curated Gift Box',
            'slug' => 'curated-gift-box',
            'description' => 'A beautifully packaged gift box for special occasions.',
            'price' => 450.00,
            'stock_quantity' => 15,
            'sku' => 'GFT-001',
            'is_active' => true,
            'is_featured' => true,
        ]);
    }
}