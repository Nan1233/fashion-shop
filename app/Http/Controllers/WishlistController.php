<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::id();
        $wishlist = Wishlist::with('product')->where('user_id', $userId)->get();
        $wishlistCount = $wishlist->count();
        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        Log::info("Wishlist Index for user {$userId}: {$wishlistCount} items, {$cartCount} in cart.");

        return view('wishlist.index', compact('wishlist', 'wishlistCount', 'cartCount'));
    }

    /**
     * Add a product to the wishlist.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $wishlistItem = Wishlist::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        if ($wishlistItem->wasRecentlyCreated) {
            Log::info("Product {$productId} added to wishlist for user {$userId}.");
            return redirect()->back()->with('success', 'Đã thêm vào danh sách yêu thích!');
        }

        Log::warning("Product {$productId} already exists in wishlist for user {$userId}.");
        return redirect()->back()->with('info', 'Sản phẩm đã có trong danh sách yêu thích!');
    }

    /**
     * Remove a product from the wishlist.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id)
    {
        $userId = Auth::id();
        $wishlist = Wishlist::where('user_id', $userId)->where('id', $id)->first();

        if ($wishlist) {
            $wishlist->delete();
            Log::info("Wishlist item {$id} removed for user {$userId}.");
            return redirect()->back()->with('success', 'Đã xóa khỏi danh sách yêu thích!');
        }

        Log::warning("Wishlist item {$id} not found for user {$userId}.");
        return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong danh sách yêu thích!');
    }

    /**
     * Toggle a product in the wishlist (add if not exist, remove if exist).
     *
     * @param int $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle($product)
    {
        $userId = Auth::id();

        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $product)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            Log::info("Product {$product} removed from wishlist for user {$userId}.");
            return response()->json([
                'status' => 'removed',
                'message' => 'Đã xóa khỏi danh sách yêu thích!',
                'wishlistCount' => Wishlist::where('user_id', $userId)->count()
            ]);
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $product
        ]);

        Log::info("Product {$product} added to wishlist for user {$userId}.");
        return response()->json([
            'status' => 'added',
            'message' => 'Đã thêm vào danh sách yêu thích!',
            'wishlistCount' => Wishlist::where('user_id', $userId)->count()
        ]);
    }
}
