@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Quản lý Đơn hàng</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Phương thức thanh toán</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>${{ $order->total }}</td>
                    <td>
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach(['pending','processing','completed','canceled'] as $status)
                                    <option value="{{ $status }}" @if($order->status==$status) selected @endif>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>{{ ucfirst($order->payment_method) ?? '-' }}</td>
                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                    <td>
    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">Chi tiết</a>
</td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Chưa có đơn hàng nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
