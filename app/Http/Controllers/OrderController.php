<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Checkout single product
     */
    public function checkoutSingle($productId)
{
    $product = Product::findOrFail($productId);
    $quantities = [$product->id => 1];
    $total = $product->price;

    return view('order.checkout', [
        'products'   => [$product],
        'quantities' => $quantities,
        'total'      => $total,
        'single'     => true, // để biết là đặt hàng trực tiếp
    ]);
}


    /**
     * Checkout cart
     */
    public function checkout()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }

        $products = $carts->map(fn($cart) => $cart->product);
        $quantities = $carts->pluck('quantity', 'product_id')->toArray();
        $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        return view('order.checkout', compact('products', 'quantities', 'total', 'carts'))
            ->with('single', false);
    }

    /**
     * Place order
     */
    public function placeOrder(Request $request)
{
    $userId = Auth::id();
    $orderItems = [];
    $total = 0;

    $request->validate([
        'payment_method' => 'required|in:cod,vnpay,momo',
        'product_id' => 'nullable|exists:products,id',
        'quantity' => 'nullable|integer|min:1',
        'cart_ids' => 'nullable|array',
        'cart_ids.*' => 'exists:carts,id,user_id,' . $userId,
    ]);

    // Order from cart
    if ($request->has('cart_ids') && !empty($request->cart_ids)) {
        $carts = Cart::with('product')
            ->whereIn('id', $request->cart_ids)
            ->where('user_id', $userId)
            ->get();

        foreach ($carts as $cart) {
            if($cart->product->stock < $cart->quantity){
                return redirect()->back()->with('error', "Sản phẩm {$cart->product->name} không đủ tồn kho!");
            }

            $orderItems[] = [
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'total' => $cart->product->price * $cart->quantity,
                'status' => 'processing',
            ];
            $total += $cart->product->price * $cart->quantity;
        }
    }
    // Single product order
    elseif ($request->has('product_id') && $request->has('quantity')) {
        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        if($product->stock < $quantity){
            return redirect()->back()->with('error', "Sản phẩm {$product->name} không đủ tồn kho!");
        }

        $orderItems[] = [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'total' => $product->price * $quantity,
            'status' => 'processing',
        ];
        $total = $product->price * $quantity;
    } else {
        return redirect()->back()->with('error', 'No products selected for order!');
    }

    // Create order
    $order = Order::create([
        'user_id' => $userId,
        'total' => $total,
        'status' => 'pending',
        'payment_method' => $request->payment_method,
    ]);

    // Create order items and decrease stock
    foreach ($orderItems as $item) {
        OrderItem::create(array_merge($item, ['order_id' => $order->id]));
        $product = Product::find($item['product_id']);
        $product->decreaseStock($item['quantity']);
    }

    // Clear cart if needed
    if ($request->has('cart_ids') && !empty($request->cart_ids)) {
        Cart::where('user_id', $userId)
            ->whereIn('id', $request->cart_ids)
            ->delete();
    }

    if ($request->payment_method === 'cod') {
        return redirect()->route('home')->with('success', 'Order placed successfully! COD chosen.');
    }

    if ($request->payment_method === 'vnpay') {
return redirect()->route('payment.vnpay', ['order_id' => $order->id]);
    } elseif ($request->payment_method === 'momo') {
        return redirect()->route('payment.momo', ['order_id' => $order->id]);
    }

    return redirect()->route('home')->with('error', 'Invalid payment method!');
}

// Update order (cancel) – trả lại stock
public function update(Request $request, Order $order)
{
    if ($order->user_id !== Auth::id()) {
        abort(403, 'Bạn không có quyền hủy đơn này.');
    }

    if (!in_array($order->status, ['pending', 'processing'])) {
        return redirect()->back()->with('error', 'Đơn hàng này không thể hủy.');
    }

    $request->validate([
        'status' => 'required|in:canceled',
    ]);

    // Cập nhật stock cho từng sản phẩm
    foreach ($order->items as $item) {
        $product = $item->product;
        $product->increaseStock($item->quantity);
    }

    $order->update([
        'status' => $request->status,
    ]);

    return redirect()->back()->with('success', 'Đơn hàng đã được hủy và tồn kho cập nhật.');
}


    /**
     * List user orders
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order.index', compact('orders'));
    }

    /**
     * Show order detail
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $orderItems = $order->items()->with('product')->get();

        return view('order.show', compact('order', 'orderItems'));
    }

    /**
     * Update order (cancel)
     */
    

    /**
     * Show review form
     */
    public function createReview(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403, 'Bạn không thể đánh giá đơn hàng này.');
        }

        $orderItems = $order->items()->with(['product', 'product.reviews' => function($q) use ($order) {
            $q->where('user_id', Auth::id())
              ->where('order_id', $order->id);
        }])->get();

        return view('order.review', compact('order', 'orderItems'));
    }

    /**
     * Store review
     */
    public function storeReview(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403, 'Bạn không thể đánh giá đơn hàng này.');
        }

        $request->validate([
            'reviews' => 'required|array',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.comment' => 'nullable|string|max:1000',
        ]);

        foreach ($request->reviews as $productId => $reviewData) {
            $review = \App\Models\Review::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'order_id' => $order->id,
            ]);

            // Chỉ update nếu chưa sửa lần đầu
            if (!$review->exists || !$review->is_edited) {
                $review->rating = $reviewData['rating'];
                $review->comment = $reviewData['comment'] ?? null;
                $review->is_edited = true;
                $review->save();
            }
        }

        return redirect()->route('orders.index')->with('success', 'Đánh giá của bạn đã được gửi.');
    }
}
