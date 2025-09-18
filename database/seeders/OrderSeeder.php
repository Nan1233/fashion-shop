<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role','customer')->first();

        // Tạo 2 đơn hàng
        for ($i=0; $i<2; $i++) {
            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0,
                'status' => 'completed',
                'payment_method' => 'COD',
            ]);

            $products = Product::inRandomOrder()->take(2)->get();
            $total = 0;

            foreach ($products as $product) {
                $qty = rand(1,2);
                $itemTotal = $product->price * $qty;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'total' => $itemTotal,
                    'status' => 'processing'
                ]);
                $total += $itemTotal;
            }

            $order->update(['total' => $total]);
        }
    }
}
