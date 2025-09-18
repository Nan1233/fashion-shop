<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Bán sản phẩm (admin)
     */
    public function sell(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantityPurchased = (int) $request->input('quantity');

        if ($product->stock >= $quantityPurchased) {
            $product->decreaseStock($quantityPurchased);
            return redirect()->back()->with('success', 'Bán sản phẩm thành công');
        } else {
            return redirect()->back()->with('error', 'Số lượng không đủ');
        }
    }

    /**
     * Top 5 sản phẩm bán chạy
     */
    public function bestSellers()
    {
        $bestSellers = Product::orderBy('sold', 'desc')->take(5)->get();

        return view('products.best_sellers', compact('bestSellers'));
    }

    /**
     * Danh sách sản phẩm (lọc / tìm kiếm)
     */
    public function index()
    {
        $categories = Category::all();

        $products = Product::with(['category', 'reviews'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($product) {
                $product->stock = (int) $product->stock;
                $product->sold  = (int) $product->sold;
                return $product;
            });

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Chi tiết sản phẩm
     */
    public function show($id)
{
    $product = Product::with(['category', 'reviews'])->findOrFail($id);
    $categories = Category::all();

    // Lấy cart count
    $cartCount = auth()->check() ? auth()->user()->cartItems()->count() : 0;

    // Lấy wishlist count (nếu user đã login)
    $wishlistCount = auth()->check() ? auth()->user()->wishlistItems()->count() : 0;

    // Các dữ liệu khác
    $userOrderIds = auth()->check() ? auth()->user()->orders()->pluck('id')->toArray() : [];

    return view('products.show', compact(
        'product', 
        'categories', 
        'cartCount', 
        'wishlistCount', 
        'userOrderIds'
    ));
}


    /**
     * Gợi ý sản phẩm dựa trên lịch sử xem
     */
    public function recommendedProducts()
    {
        if(!auth()->check()) return collect();

        $lastViewedIds = UserActivity::where('user_id', auth()->id())
                            ->where('type', 'view')
                            ->latest()
                            ->take(5)
                            ->pluck('product_id');

        $categories = Product::whereIn('id', $lastViewedIds)->pluck('category_id');

        $recommended = Product::whereIn('category_id', $categories)
                            ->whereNotIn('id', $lastViewedIds)
                            ->take(5)
                            ->get();

        return $recommended;
    }

    /**
     * Form thêm sản phẩm
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'slug'        => 'required|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric',
            'image'       => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công');
    }

    /**
     * Thêm vào wishlist
     */
    public function addToWishlist($id)
    {
        if(auth()->check()) {
            Wishlist::firstOrCreate([
                'user_id' => auth()->id(),
                'product_id' => $id
            ]);
            return redirect()->back()->with('success', 'Đã thêm vào danh sách yêu thích');
        }
        return redirect()->route('login');
    }

    /**
     * Xóa khỏi wishlist
     */
    public function removeFromWishlist($id)
    {
        if(auth()->check()) {
            Wishlist::where('user_id', auth()->id())
                    ->where('product_id', $id)
                    ->delete();
            return redirect()->back()->with('success', 'Đã xóa khỏi danh sách yêu thích');
        }
        return redirect()->route('login');
    }
}
