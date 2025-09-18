<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Áo', 'slug' => 'ao', 'description' => 'Các loại áo thời trang nam & nữ'],
            ['name' => 'Quần', 'slug' => 'quan', 'description' => 'Các loại quần jeans, kaki, short'],
            ['name' => 'Giày dép', 'slug' => 'giay-dep', 'description' => 'Giày sneaker, boot, dép thời trang'],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'description' => 'Túi xách, mũ, thắt lưng, mắt kính'],
            ['name' => 'Váy', 'slug' => 'vay', 'description' => 'Váy thời trang nữ'],
            ['name' => 'Áo khoác', 'slug' => 'ao-khoac', 'description' => 'Áo khoác, blazer, jacket'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
