@extends('layouts.app')

@section('title', 'Đánh giá đơn hàng #'.$order->id)

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold" style="color:#ff6b6b;">
        Đánh giá đơn hàng #{{ $order->id }}
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('orders.review.store', $order->id) }}" method="POST">
        @csrf
        <div class="row">
            @foreach($orderItems as $item)
                @php
                    // Lấy review đúng user & order
                    $review = $item->product->reviews->first();
                @endphp

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm p-3 h-100">
                        <h5>{{ $item->product->name }}</h5>
                        <p>Giá: {{ number_format($item->product->price,0,',','.') }}₫</p>

                        @if($review && $review->is_edited)
                            {{-- Hiển thị read-only --}}
                            <p><strong>Đánh giá: </strong>
                                @for($i=1; $i<=5; $i++)
                                    <span style="color: {{ $i <= $review->rating ? '#ffcc00' : '#ccc' }}">★</span>
                                @endfor
                            </p>
                            <p><strong>Nhận xét: </strong>{{ $review->comment ?? 'Không có' }}</p>
                        @else
                            {{-- Form đánh giá lần đầu --}}
                            <div class="mb-3">
                                <label class="d-block">Đánh giá sao:</label>
                                <div class="star-rating" data-product="{{ $item->product->id }}">
                                    @for($i=1; $i<=5; $i++)
                                        <input type="radio" id="star{{ $i }}-{{ $item->product->id }}" 
                                               name="reviews[{{ $item->product->id }}][rating]" 
                                               value="{{ $i }}" class="d-none"
                                               @if($review && $review->rating == $i) checked @endif>
                                        <label for="star{{ $i }}-{{ $item->product->id }}" 
                                               class="star {{ $review && $i <= $review->rating ? 'active' : '' }}">★</label>
                                    @endfor
                                </div>
                            </div>

                            <div>
                                <label>Nhận xét:</label>
                                <textarea name="reviews[{{ $item->product->id }}][comment]" class="form-control" rows="3">{{ $review->comment ?? '' }}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @php
            $canReview = $orderItems->contains(function($item) {
                $review = $item->product->reviews->first();
                return !$review || !$review->is_edited;
            });
        @endphp

        <div class="d-flex gap-2">
            @if($canReview)
                <button type="submit" class="btn btn-success">Gửi đánh giá</button>
            @endif
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>

<style>
    .star-rating { font-size: 1.8rem; cursor: pointer; }
    .star { color: #ccc; margin-right: 5px; }
    .star.active { color: #ffcc00; }
</style>

<script>
document.querySelectorAll('.star-rating').forEach(rating => {
    const stars = rating.querySelectorAll('.star');
    const inputs = rating.querySelectorAll('input');

    const checked = [...inputs].find(input => input.checked);
    if (checked) {
        const index = [...inputs].indexOf(checked);
        stars.forEach((s, i) => s.classList.toggle('active', i <= index));
    }

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            inputs[index].checked = true;
            stars.forEach((s, i) => s.classList.toggle('active', i <= index));
        });
    });
});
</script>
@endsection
