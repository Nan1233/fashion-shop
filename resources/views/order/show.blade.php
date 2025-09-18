@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #'.$order->id.' - Fashion Shop')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold" style="color:#ff6b6b;">Chi tiết đơn hàng #{{ $order->id }}</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }}₫</p>
            <p><strong>Phương thức thanh toán:</strong> {{ ucfirst($order->payment_method) ?? 'Chưa chọn' }}</p>
            <p><strong>Trạng thái:</strong> 
                <span class="badge 
                @if($order->status=='pending') bg-warning
                @elseif($order->status=='processing') bg-info
                @elseif($order->status=='completed') bg-success
                @elseif($order->status=='canceled') bg-danger
                @endif">
                @if($order->status=='pending') Chờ xử lý
                @elseif($order->status=='processing') Đang xử lý
                @elseif($order->status=='completed') Hoàn thành
                @elseif($order->status=='canceled') Đã hủy
                @endif
                </span>
            </p>
        </div>
    </div>

    <h5 class="mb-3">Sản phẩm:</h5>
    <div class="row">
        @foreach($orderItems as $item)
            @php
                $review = $item->product->reviews
                    ->where('user_id', Auth::id())
                    ->where('order_id', $order->id)
                    ->first();
            @endphp

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card product-card h-100 shadow-sm">
                    @if($item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="card-img-top" alt="{{ $item->product->name }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $item->product->name }}</h6>
                        <p class="product-price">{{ number_format($item->total, 0, ',', '.') }}₫</p>
                        <p class="card-text">Số lượng: {{ $item->quantity }}</p>

                        {{-- Hiển thị đánh giá nếu có --}}
                        @if($order->status == 'completed')
                            @if($review)
                                <p><strong>Đánh giá:</strong>
                                    @for($i=1; $i<=5; $i++)
                                        <span style="color: {{ $i <= $review->rating ? '#ffcc00' : '#ccc' }}">★</span>
                                    @endfor
                                </p>
                                <p><strong>Nhận xét:</strong> {{ $review->comment ?? 'Không có' }}</p>
                            @else
                                <p class="text-muted">Chưa có đánh giá</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
