<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role','customer')->first();

        // Lấy 1 sản phẩm ngẫu nhiên để tạo cart (vì product_id không null)
        $product = Product::inRandomOrder()->first();

        Cart::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $product->id],
            ['created_at' => now(), 'updated_at' => now()]
        );
    }
}
