@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Ảnh sản phẩm -->
        <div class="col-md-5">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow-sm">
            @else
                <img src="https://via.placeholder.com/400x400" class="img-fluid rounded shadow-sm">
            @endif
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-7">
            <h2 class="mb-3">{{ $product->name }}</h2>
            <p><strong>Danh mục:</strong> {{ $product->category->name }}</p>
            <p>{{ $product->description }}</p>
            <p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }}₫</p>

            @php
                $avgRating = round($product->reviews->avg('rating'), 1);
            @endphp
            @if($avgRating > 0)
                <p><strong>Đánh giá trung bình:</strong> 
                    <span class="text-warning">⭐ {{ $avgRating }}/5</span> 
                    ({{ $product->reviews->count() }} đánh giá)
                </p>
            @endif
            
            @if($product->stock > 0)
                <p><strong>Trạng thái:</strong> <span class="text-success">Còn hàng</span></p>
                <p><strong>Số lượng tồn:</strong> {{ $product->stock }}</p>
            @else
                <p><strong>Trạng thái:</strong> <span class="text-danger">Hết hàng</span></p>
                <p><strong>Số lượng tồn:</strong> 0</p>
            @endif

            <!-- Buttons -->
            <div class="mt-4 d-flex gap-2">
                @auth
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Thêm vào giỏ</button>
                        </form>
                        <a href="{{ route('order.create', $product->id) }}" class="btn btn-primary">Mua ngay</a>
                    @else
                        <button class="btn btn-secondary" disabled>Hết hàng</button>
                        <button class="btn btn-secondary" disabled>Hết hàng</button>
                    @endif

                    <!-- Thêm vào yêu thích -->
                    <button class="toggle-wishlist btn btn-outline-warning" 
                            data-product-id="{{ $product->id }}"
                            data-is-wishlisted="{{ auth()->user()->wishlistItems->contains($product->id) ? 'true' : 'false' }}">
                        <i class="bi bi-heart{{ auth()->user()->wishlistItems->contains($product->id) ? '-fill' : '' }}"></i>
                        {{ auth()->user()->wishlistItems->contains($product->id) ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}
                    </button>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn btn-success">Thêm vào giỏ</a>
                    <a href="{{ route('login') }}" class="btn btn-primary">Mua ngay</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-warning"><i class="bi bi-heart"></i> Thêm vào yêu thích</a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Đánh giá sản phẩm -->
    <hr class="my-4">
    <h4>Đánh giá sản phẩm</h4>

    @forelse($product->latestReviews as $review)
        <div class="border p-2 mb-2 rounded">
            <strong>{{ $review->user->name }}</strong> - {{ $review->rating }} ⭐
            <p class="mb-1">{{ $review->comment }}</p>
            <small class="text-muted">{{ $review->updated_at->format('d-m-Y H:i') }}</small>
        </div>
    @empty
        <p>Chưa có đánh giá nào cho sản phẩm này.</p>
    @endforelse
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const wishlistBtn = document.querySelector('.toggle-wishlist');

    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const isWishlisted = this.dataset.isWishlisted === 'true';
            const token = "{{ csrf_token() }}";

            fetch("{{ url('wishlist/toggle') }}/" + productId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product: productId })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'added') {
                    this.dataset.isWishlisted = 'true';
                    this.innerHTML = '<i class="bi bi-heart-fill"></i> Bỏ yêu thích';
                } else if(data.status === 'removed') {
                    this.dataset.isWishlisted = 'false';
                    this.innerHTML = '<i class="bi bi-heart"></i> Thêm vào yêu thích';
                }
            })
            .catch(err => console.error('Error:', err));
        });
    }
});
</script>
@endsection
@endsection
