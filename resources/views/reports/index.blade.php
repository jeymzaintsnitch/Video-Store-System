@extends('layouts.app')

@section('title', 'Rental Demand Reports')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }

    .page-header { margin-bottom: 2rem; }
    .page-header h3 { font-weight: 700; color: #e8eaed; font-size: 22px; margin: 0; }
    .page-header p { color: #9aa0a6; font-size: 14px; margin: 4px 0 0; }

    .stat-card { background: #161625; border: 1px solid #2d2d3d; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 16px; }
    .stat-icon { width: 52px; height: 52px; min-width: 52px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .stat-label { font-size: 12px; color: #9aa0a6; font-weight: 500; }
    .stat-value { font-size: 26px; font-weight: 700; color: #e8eaed; line-height: 1.2; }

    .icon-blue   { background: rgba(26,115,232,0.15); color: #8ab4f8; border: 1px solid rgba(26,115,232,0.3); }
    .icon-green  { background: rgba(30,142,62,0.15);  color: #81c995; border: 1px solid rgba(30,142,62,0.3); }
    .icon-red    { background: rgba(217,48,37,0.15);  color: #f28b82; border: 1px solid rgba(217,48,37,0.3); }
    .icon-yellow { background: rgba(242,153,0,0.15);  color: #fde293; border: 1px solid rgba(242,153,0,0.3); }

    .panel { background: #161625; border: 1px solid #2d2d3d; border-radius: 12px; padding: 1.5rem; }
    .panel-title { font-size: 15px; font-weight: 700; color: #e8eaed; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px; }

    .demand-bar { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .demand-bar-label { font-size: 13px; color: #e8eaed; min-width: 170px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500; }
    .demand-bar-track { flex: 1; height: 10px; background: #222235; border-radius: 5px; overflow: hidden; }
    .demand-bar-fill  { height: 100%; border-radius: 5px; transition: width 0.6s ease; }
    .fill-hot  { background: linear-gradient(90deg, #1a73e8, #8ab4f8); }
    .fill-cold { background: linear-gradient(90deg, #2d2d3d, #4a4a5a); }
    .demand-bar-count { font-size: 13px; font-weight: 700; color: #9aa0a6; min-width: 32px; text-align: right; }

    .genre-badge { font-size: 10px; padding: 2px 8px; border-radius: 4px; background: rgba(217,48,37,0.2); color: #f28b82; border: 1px solid rgba(217,48,37,0.3); margin-left: 6px; }
    .chart-canvas-wrapper { position: relative; height: 220px; }
</style>

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3>📊 Rental Demand Report</h3>
            <p>Admin analytics on movie popularity and rental activity</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Dashboard</a>
    </div>

    {{-- Summary Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon icon-blue">📤</div>
                <div>
                    <div class="stat-label">Total Rentals</div>
                    <div class="stat-value">{{ $totalRentals }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon icon-blue">🔵</div>
                <div>
                    <div class="stat-label">Active Rentals</div>
                    <div class="stat-value">{{ $activeRentals }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon icon-green">✅</div>
                <div>
                    <div class="stat-label">Returned</div>
                    <div class="stat-value">{{ $returnedRentals }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon icon-red">⚠️</div>
                <div>
                    <div class="stat-label">Overdue</div>
                    <div class="stat-value">{{ $overdueRentals }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        {{-- Top 10 Most Rented --}}
        <div class="col-xl-6">
            <div class="panel">
                <div class="panel-title">🔥 Top 10 Most In-Demand Movies</div>
                @php $maxTop = $topMovies->max('total_rentals') ?: 1; @endphp
                @forelse($topMovies as $i => $movie)
                    <div class="demand-bar">
                        <div class="demand-bar-label" title="{{ $movie->title }}">
                            <span style="color:#8ab4f8; font-weight:700; margin-right:6px;">{{ $i+1 }}.</span>
                            {{ $movie->title }}
                            <span class="genre-badge">{{ $movie->category->name ?? '' }}</span>
                        </div>
                        <div class="demand-bar-track">
                            <div class="demand-bar-fill fill-hot" style="width: {{ ($movie->total_rentals / $maxTop) * 100 }}%;"></div>
                        </div>
                        <div class="demand-bar-count">{{ $movie->total_rentals }}</div>
                    </div>
                @empty
                    <p class="text-muted small text-center py-3">No rental data yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Bottom 10 Least Rented --}}
        <div class="col-xl-6">
            <div class="panel">
                <div class="panel-title">❄️ Bottom 10 Least In-Demand Movies</div>
                @php $maxLow = max($lowMovies->max('total_rentals'), 1); @endphp
                @forelse($lowMovies as $i => $movie)
                    <div class="demand-bar">
                        <div class="demand-bar-label" title="{{ $movie->title }}">
                            <span style="color:#9aa0a6; font-weight:700; margin-right:6px;">{{ $i+1 }}.</span>
                            {{ $movie->title }}
                            <span class="genre-badge">{{ $movie->category->name ?? '' }}</span>
                        </div>
                        <div class="demand-bar-track">
                            <div class="demand-bar-fill fill-cold" style="width: {{ ($movie->total_rentals / $maxLow) * 100 }}%;"></div>
                        </div>
                        <div class="demand-bar-count">{{ $movie->total_rentals }}</div>
                    </div>
                @empty
                    <p class="text-muted small text-center py-3">No rental data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Monthly Trend Chart --}}
    <div class="panel">
        <div class="panel-title">📈 Monthly Rental Trend (Last 6 Months)</div>
        @if($monthlyTrend->isEmpty())
            <p class="text-muted small text-center py-3">No rental data in the last 6 months.</p>
        @else
            <div class="chart-canvas-wrapper">
                <canvas id="monthlyChart"></canvas>
            </div>
        @endif
    </div>

</div>

<script>
@if($monthlyTrend->isNotEmpty())
const ctx = document.getElementById('monthlyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
        datasets: [{
            label: 'Rentals',
            data: {!! json_encode($monthlyTrend->pluck('count')) !!},
            backgroundColor: 'rgba(26,115,232,0.5)',
            borderColor: '#1a73e8',
            borderWidth: 1.5,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: '#9aa0a6' } }
        },
        scales: {
            x: { ticks: { color: '#9aa0a6' }, grid: { color: '#222235' } },
            y: { ticks: { color: '#9aa0a6', stepSize: 1 }, grid: { color: '#222235' }, beginAtZero: true }
        }
    }
});
@endif
</script>
@endsection
