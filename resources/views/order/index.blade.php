@extends('layouts.app')

@section('title', 'Lịch sử đơn hàng - Fashion Shop')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold" style="color:#ff6b6b;">Lịch sử đơn hàng</h2>

    @forelse($orders as $order)
        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Đơn hàng #{{ $order->id }}</h5>
                    <p class="mb-1">Tổng tiền: <strong>{{ number_format($order->total, 0, ',', '.') }}₫</strong></p>
                    <p class="mb-0">Trạng thái: 
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

                <div class="d-flex gap-2">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">Chi tiết</a>

                    @if(in_array($order->status, ['pending','processing']))
                        <form action="{{ route('orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="canceled">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">Hủy đơn</button>
                        </form>
                    @endif

                    {{-- ✅ Nút đánh giá chuyển sang trang khác --}}
                    @if($order->status == 'completed' && !$order->review)
                        <a href="{{ route('orders.review.create', $order->id) }}" class="btn btn-success">Đánh giá</a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-center fw-bold">Bạn chưa có đơn hàng nào.</p>
    @endforelse
</div>
@endsection
