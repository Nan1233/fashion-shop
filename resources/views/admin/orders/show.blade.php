@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Chi tiết Đơn hàng #{{ $order->id }}</h2>

    <p><strong>Khách hàng:</strong> {{ $order->user->name }}</p>
    <p><strong>Tổng tiền:</strong> ${{ $order->total }}</p>
    <p><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Phương thức thanh toán:</strong> {{ ucfirst($order->payment_method) }}</p>
    <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>

    <h4 class="mt-4">Sản phẩm</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>${{ $item->product->price }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ $item->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection
