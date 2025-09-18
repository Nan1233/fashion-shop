<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\Product;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role','customer')->first();
        $products = Product::inRandomOrder()->take(5)->get();

        foreach ($products as $product) {
            Wishlist::updateOrCreate([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
        }
    }
}
