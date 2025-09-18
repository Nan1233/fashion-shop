@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
<div class="container">
    <h2 class="text-center mb-4" style="color: #333;">Danh sách yêu thích</h2>

    @if($wishlist->isEmpty())
        <p class="text-center text-muted">Chưa có sản phẩm nào trong wishlist.</p>
    @else
        <ul style="list-style: none; padding: 0; max-width: 600px; margin: auto;">
            @foreach($wishlist as $item)
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
                    <div>
                        <a href="{{ route('products.show', $item->product->id) }}" class="text-decoration-none text-dark">
                            <strong>{{ $item->product->name }}</strong>
                            <p style="color: #e74c3c; margin: 0;">{{ number_format($item->product->price, 0, ',', '.') }} VND</p>
                        </a>
                    </div>
                    <form method="POST" action="{{ route('wishlist.remove', $item->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<style>
    li:hover { background-color: #f1f1f1; }
    button:hover { background-color: #c0392b; }
    a:hover strong { text-decoration: underline; }
</style>
@endsection
