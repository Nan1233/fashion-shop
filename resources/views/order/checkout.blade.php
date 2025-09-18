@extends('layouts.app')

@section('styles')
<style>
.container {
    max-width: 700px;
}

/* Danh sách sản phẩm */
.list-group-item {
    font-size: 1rem;
}
.list-group-item strong {
    font-size: 1.1rem;
}

/* Nút thanh toán */
.payment-methods {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 30px;
}
.payment-methods form {
    flex: 1 1 250px;
}

/* Button đẹp, hover nhẹ */
.payment-methods button {
    width: 100%;
    padding: 25px;
    font-size: 1.15rem;
    font-weight: 600;
    border-radius: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
}
.payment-methods button:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

/* VNPay xanh lá */
.btn-vnpay {
    background-color: #28a745;
    color: #fff;
    border: 2px solid #28a745;
}
.btn-vnpay:hover {
    background-color: #218838;
}

/* COD xanh dương */
.btn-cod {
    background-color: #007bff;
    color: #fff;
    border: 2px solid #007bff;
}
.btn-cod:hover {
    background-color: #0069d9;
}

/* Icon bên trong button không chặn click */
.payment-methods i {
    pointer-events: none;
}

/* Mobile */
@media(max-width: 576px) {
    .payment-methods {
        flex-direction: column;
    }
}
</style>
@endsection

@section('content')
<div class="container mt-5">
    <h3 class="mb-4 text-center">Thanh toán</h3>

    <!-- Danh sách sản phẩm -->
    <ul class="list-group mb-4 shadow-sm">
        @foreach(isset($single) && $single ? $products : $carts as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ isset($single) && $single ? $item->name : $item->product->name }} x
                {{ isset($single) && $single ? $quantities[$item->id] : $item->quantity }}
                <span class="fw-bold">
                    {{ number_format((isset($single) && $single ? $item->price * $quantities[$item->id] : $item->product->price * $item->quantity),0,',','.') }}₫
                </span>
            </li>
        @endforeach
        <li class="list-group-item d-flex justify-content-between bg-light fw-bold">
            Tổng cộng
            <span>{{ number_format($total,0,',','.') }}₫</span>
        </li>
    </ul>

    <h5 class="mb-3">Chọn phương thức thanh toán:</h5>
    <div class="payment-methods">

        <!-- VNPay -->
        <form action="{{ route('payment.vnpay') }}" method="POST">
            @csrf
            @if(isset($single) && $single)
                <input type="hidden" name="single" value="1">
                <input type="hidden" name="product_id" value="{{ $products[0]->id }}">
                <input type="hidden" name="quantity" value="{{ $quantities[$products[0]->id] }}">
            @else
                @foreach($carts as $cart)
                    <input type="hidden" name="cart_ids[]" value="{{ $cart->id }}">
                @endforeach
            @endif
            <button type="submit" class="btn-vnpay">
                <i class="bi bi-credit-card"></i> Thanh toán VNPay
            </button>
        </form>

        <!-- COD -->
        <form action="{{ route('order.place') }}" method="POST">
            @csrf
            <input type="hidden" name="payment_method" value="cod">
            @if(isset($single) && $single)
                <input type="hidden" name="product_id" value="{{ $products[0]->id }}">
                <input type="hidden" name="quantity" value="{{ $quantities[$products[0]->id] }}">
            @else
                @foreach($carts as $cart)
                    <input type="hidden" name="cart_ids[]" value="{{ $cart->id }}">
                @endforeach
            @endif
            <button type="submit" class="btn-cod">
                <i class="bi bi-cash-stack"></i> Thanh toán khi nhận hàng
            </button>
        </form>

    </div>
</div>
@endsection
