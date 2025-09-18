<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Hiển thị form đánh giá
    public function create(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            return back()->with('error', 'Bạn không thể đánh giá đơn hàng này.');
        }

        // Lấy items kèm product và review của user hiện tại
        $orderItems = $order->items()
            ->with(['product', 'product.reviews' => function($q) use ($order) {
                $q->where('user_id', Auth::id())
                  ->where('order_id', $order->id);
            }])->get();

        return view('orders.review', compact('order', 'orderItems'));
    }

    // Lưu hoặc sửa đánh giá
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            return back()->with('error', 'Bạn không thể đánh giá đơn hàng này.');
        }

        $request->validate([
            'reviews' => 'required|array',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.comment' => 'nullable|string|max:1000',
        ]);

        $updated = false;

        foreach ($order->items as $item) {
            $productId = $item->product_id;

            if (!isset($request->reviews[$productId])) continue;

            $rating = $request->reviews[$productId]['rating'];
            $comment = $request->reviews[$productId]['comment'] ?? null;

            $review = Review::firstOrNew([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'product_id' => $productId
            ]);

            // Nếu đã sửa rồi, bỏ qua
            if ($review->exists && $review->is_edited) continue;

            $review->rating = $rating;
            $review->comment = $comment;

            // Nếu là lần sửa đầu tiên, đánh dấu is_edited
            if ($review->exists) {
                $review->is_edited = true;
            } else {
                $review->is_edited = false;
            }

            $review->save();
            $updated = true;
        }

        $msg = $updated ? 'Đánh giá đã được lưu.' : 'Bạn đã sửa đánh giá rồi, không thể chỉnh sửa thêm.';

        return redirect()->route('orders.review.create', $order->id)
            ->with($updated ? 'success' : 'info', $msg);
    }
}
