@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        <!-- Toggle button hiển thị Admin Panel -->
        <div class="col-12 mb-3">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#adminPanel" aria-expanded="false" aria-controls="adminPanel">
                <i class="bi bi-list"></i> Admin Panel
            </button>
        </div>

        <!-- Admin Panel collapse -->
        <div class="collapse col-12 mb-4" id="adminPanel">
            <div class="card shadow-sm">
                <div class="card-body d-flex flex-wrap gap-3 justify-content-start">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="bi bi-tags me-1"></i> Danh mục
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-success d-flex align-items-center">
                        <i class="bi bi-box-seam me-1"></i> Sản phẩm
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-warning d-flex align-items-center">
                        <i class="bi bi-cart-check me-1"></i> Đơn hàng
                    </a>
                </div>
            </div>
        </div>

        <!-- Main dashboard content -->
        <main class="col-12">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-currency-dollar fs-1 text-success me-3"></i>
                            <div>
                                <h5 class="card-title">Tổng doanh thu</h5>
                                <p class="card-text fs-5">
                                    {{ number_format($revenues->sum('total'), 0, ',', '.') }} ₫
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-box fs-1 text-primary me-3"></i>
                            <div>
                                <h5 class="card-title">Sản phẩm bán chạy</h5>
                                <p class="card-text fs-5">{{ $topProducts->first() ? $topProducts->first()->product->name : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-people fs-1 text-warning me-3"></i>
                            <div>
                                <h5 class="card-title">Tổng khách hàng</h5>
                                <p class="card-text fs-5">{{ \App\Models\User::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ doanh thu -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5>Doanh thu 7 ngày gần nhất</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>

            <!-- Top sản phẩm bán chạy -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Top 10 sản phẩm bán chạy</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
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
            </div>

        </main>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('revenueChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($revenues->pluck('date')),
        datasets: [{
            label: 'Doanh thu',
            data: @json($revenues->pluck('total')),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endsection
