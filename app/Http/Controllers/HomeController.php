<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ---------------- SẢN PHẨM GỢI Ý ----------------
        $suggestedProducts = collect();

        if ($user) {
            // Sản phẩm đã mua
            $purchasedIds = Order::where('user_id', $user->id)
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->pluck('order_items.product_id')->toArray();

            // Sản phẩm yêu thích
            $favoritedIds = \DB::table('wishlists')->where('user_id', $user->id)
                ->pluck('product_id')->toArray();

            // Sản phẩm đã xem (nếu có bảng product_views)
            $viewedIds = \DB::table('user_activities')
                ->where('user_id', $user->id)
                ->where('type', 'view')
                ->pluck('product_id')
                ->toArray();

            $allIds = array_unique(array_merge($purchasedIds, $favoritedIds, $viewedIds));

            if (!empty($allIds)) {
                $suggestedProducts = Product::with('reviews')
                    ->whereIn('id', $allIds)
                    ->take(8)
                    ->get();
            }
        }

        // Nếu chưa đủ 8 sản phẩm thì lấy thêm random
        $alreadyIds = $suggestedProducts->pluck('id')->toArray();
        if (count($alreadyIds) < 8) {
            $additional = Product::with('reviews')
                ->whereNotIn('id', $alreadyIds)
                ->inRandomOrder()
                ->take(8 - count($alreadyIds))
                ->get();

            $suggestedProducts = $suggestedProducts->concat($additional);
        }

        // ---------------- SẢN PHẨM HOT ----------------
        $hotProducts = Product::with('reviews')
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(8)
            ->get();

        // ---------------- SẢN PHẨM MỚI ----------------
        $newProducts = Product::with('reviews')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        // ---------------- DANH MỤC ----------------
        $categories = Category::all();

        // ---------------- TẤT CẢ SẢN PHẨM ----------------
        $products = Product::with('reviews')->orderBy('id', 'desc')->paginate(20);

        return view('home', compact(
            'suggestedProducts',
            'hotProducts',
            'newProducts',
            'categories',
            'products'
        ));
    }
}