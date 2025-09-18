@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Top 10 Sản phẩm bán chạy</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th>Số lượng bán</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->total_sold }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
