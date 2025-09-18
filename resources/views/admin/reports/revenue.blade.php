@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Doanh thu theo ngày</h3>
    <canvas id="revenueChart" height="100"></canvas>
</div>

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
            tension: 0.3
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endsection
