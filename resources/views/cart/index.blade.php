@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="cart-container">
    <h3>Giỏ hàng của bạn</h3>

    @if($cartItems->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th></th> <!-- checkbox chọn -->
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $cart)
                <tr data-id="{{ $cart->id }}">
                    <td>
                        <input type="checkbox" class="select-cart-item" checked>
                    </td>
                    <td>{{ $cart->product->name }}</td>
                    <td class="item-price">{{ number_format($cart->product->price, 0, ',', '.') }}₫</td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity-input" value="{{ $cart->quantity }}" min="1" style="width: 70px;">
                    </td>
                    <td class="item-total">{{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}₫</td>
                    <td>
                        <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer tổng tiền + nút mua hàng -->
    <div class="cart-footer mt-3">
        <h5>
            Tổng số sản phẩm: <span id="total-quantity">{{ $cartItems->sum('quantity') }}</span> |
            Tổng tiền: <span id="total-price">{{ number_format($cartItems->reduce(function($carry, $cart){ return $carry + $cart->product->price * $cart->quantity; }, 0), 0, ',', '.') }}₫</span>
        </h5>
        <button id="checkout-selected" class="btn btn-primary">Thanh toán sản phẩm chọn</button>
    </div>

    @else
        <p>Giỏ hàng trống.</p>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    function updateTotals() {
        let totalQuantity = 0;
        let totalPrice = 0;

        $('tbody tr').each(function() {
            const $row = $(this);
            const quantity = parseInt($row.find('.quantity-input').val());
            const price = parseFloat($row.find('.item-price').text().replace(/[₫,.]/g, ''));
            const selected = $row.find('.select-cart-item').is(':checked');

            $row.find('.item-total').text(formatVND(price * quantity));

            if (selected) {
                totalQuantity += quantity;
                totalPrice += price * quantity;

                // Gửi AJAX tự động cập nhật số lượng
                $.post("{{ url('/cart/update') }}/" + $row.data('id'), {
                    _token: "{{ csrf_token() }}",
                    quantity: quantity
                });
            }
        });

        $('#total-quantity').text(totalQuantity);
        $('#total-price').text(formatVND(totalPrice));
    }

    // Cập nhật khi thay đổi số lượng hoặc chọn checkbox
    $(document).on('input change', '.quantity-input, .select-cart-item', updateTotals);

    // Thanh toán sản phẩm chọn
    $('#checkout-selected').click(function() {
        let selectedIds = [];
        $('tbody tr').each(function() {
            if ($(this).find('.select-cart-item').is(':checked')) {
                selectedIds.push($(this).data('id'));
            }
        });

        if (selectedIds.length === 0) {
            alert('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán');
            return;
        }

        // Tạo form POST động để gửi đến checkout
        let form = $('<form>', {
            action: "{{ route('checkout') }}",
            method: 'POST'
        }).append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: "{{ csrf_token() }}"
        })).append($('<input>', {
            type: 'hidden',
            name: 'cart_ids',
            value: selectedIds.join(',')
        }));

        $('body').append(form);
        form.submit();
    });

    updateTotals();
});
</script>
@endsection
