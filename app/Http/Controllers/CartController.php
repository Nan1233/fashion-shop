<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->get();
        $cartCount = $cartItems->sum('quantity'); // Tổng số sản phẩm

        return view('cart.index', [
            'cartItems' => $cartItems,
            'cartCount' => $cartCount
        ]);
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request, $productId)
    {
        $userId = Auth::id();

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId, 'product_id' => $productId],
            ['quantity' => 0]
        );

        $cart->quantity += $request->input('quantity', 1);
        $cart->save();

        // Cập nhật tổng số lượng vào session
        $cartCount = Cart::where('user_id', $userId)->sum('quantity');
        session(['cartCount' => $cartCount]);

        // Nếu là AJAX trả về JSON
        if($request->ajax()){
            return response()->json([
                'cartCount' => $cartCount
            ]);
        }

        return back()->with('success', 'Product added to cart successfully!');
    }

    // Cập nhật số lượng (AJAX)
    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->quantity = $request->quantity;
        $cart->save();

        $total = $cart->product->price * $cart->quantity;
        $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json([
            'total' => $total,
            'cartCount' => $cartCount
        ]);
    }

    // Xóa sản phẩm khỏi giỏ (AJAX)
    // Xóa sản phẩm khỏi giỏ
public function remove($id)
{
    $cart = Cart::findOrFail($id);
    $cart->delete();

    // Cập nhật tổng số lượng vào session
    $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');
    session(['cartCount' => $cartCount]);

    return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
}

    
}
