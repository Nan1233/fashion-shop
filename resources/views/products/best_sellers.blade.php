@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Sản phẩm bán chạy</h3>
    <div class="row">
        @forelse($bestSellers as $product)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100 product-card">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/400x400" class="card-img-top" alt="{{ $product->name }}">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-truncate">{{ $product->description }}</p>
                        <p class="text-danger fw-bold">{{ number_format($product->price,0,',','.') }}₫</p>

                        <div class="mt-auto d-flex gap-2">
                            @auth
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Add to Cart</button>
                                    </form>
                                    <a href="{{ route('order.create', $product->id) }}" class="btn btn-primary btn-sm">Order</a>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Out of Stock</button>
                                    <button class="btn btn-secondary btn-sm" disabled>Out of Stock</button>
                                @endif
                            @endauth

                            @guest
                                <a href="{{ route('login') }}" class="btn btn-success btn-sm">Add to Cart</a>
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Order</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Chưa có sản phẩm bán chạy.</p>
        @endforelse
    </div>
</div>
@endsection
