<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function index() {
        $userId = Auth::id();
        $viewedProductIds = UserActivity::where('user_id', $userId)
                              ->where('type', 'view')
                              ->pluck('product_id')
                              ->toArray();

        $recommended = Product::whereIn('category_id', function($q) use ($viewedProductIds) {
            $q->select('category_id')
              ->from('products')
              ->whereIn('id', $viewedProductIds);
        })->take(10)->get();

        return view('recommendation', compact('recommended'));
    }
}
