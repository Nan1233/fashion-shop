<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']); // Chỉ admin
    }

    // Hiển thị tất cả đơn hàng
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at','desc')->get();
        return view('admin.orders.manage', compact('orders'));
    }

    // Cập nhật trạng thái đơn hàng
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,canceled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success','Cập nhật trạng thái đơn hàng thành công!');
    }
    public function show(Order $order)
{
    // load user và items cùng product
    $order->load('user', 'items.product');

    // Chú ý đường dẫn "orders" plural
    return view('admin.orders.show', compact('order'));
}


}
