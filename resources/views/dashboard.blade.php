@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    body { background-color: #0f0f15; color: #e4e6eb; }

    /* Stats grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 1400px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (max-width: 768px)  { .stats-grid { grid-template-columns: repeat(2, 1fr); } }

    .dashboard-card {
        background: #161625;
        border: 1px solid #2d2d3d;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        padding: 1.1rem;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
    }

    .icon-block { width: 46px; height: 46px; min-width: 46px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 12px; }
    .icon-blue   { background: rgba(26,115,232,0.15);  color: #8ab4f8; border: 1px solid rgba(26,115,232,0.3); }
    .icon-green  { background: rgba(30,142,62,0.15);   color: #81c995; border: 1px solid rgba(30,142,62,0.3); }
    .icon-yellow { background: rgba(242,153,0,0.15);   color: #fde293; border: 1px solid rgba(242,153,0,0.3); }
    .icon-red    { background: rgba(217,48,37,0.15);   color: #f28b82; border: 1px solid rgba(217,48,37,0.3); }
    .icon-purple { background: rgba(147,52,230,0.15);  color: #c58af9; border: 1px solid rgba(147,52,230,0.3); }
    .icon-teal   { background: rgba(0,184,163,0.15);   color: #68d0c6; border: 1px solid rgba(0,184,163,0.3); }
    .icon-orange { background: rgba(255,120,50,0.15);  color: #ffb07a; border: 1px solid rgba(255,120,50,0.3); }

    .stat-text-container { display: flex; flex-direction: column; }
    .stat-label { font-size: 11px; color: #9aa0a6; margin-bottom: 2px; font-weight: 500; }
    .stat-value { font-size: 22px; font-weight: 700; color: #e8eaed; line-height: 1.2; }

    /* Panels */
    .panel-container { background: #161625; border: 1px solid #2d2d3d; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); padding: 1.5rem; height: 100%; }
    .section-title { font-size: 15px; font-weight: 600; color: #e8eaed; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px; }

    /* Activity table */
    .table-custom { margin-bottom: 0; width: 100%; }
    .table-custom thead th { font-size: 12px; color: #e8eaed; border-bottom: 2px solid #2d2d3d; padding: 12px 16px; font-weight: 700; text-align: left; }
    .table-custom tbody td { font-size: 13px; color: #9aa0a6; border-bottom: 1px solid #2d2d3d; padding: 12px 16px; vertical-align: middle; }
    .table-custom tbody tr:last-child td { border-bottom: none; }

    .badge-action { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 4px; display: inline-block; }
    .bg-create  { background-color: rgba(30,142,62,0.2);   color: #81c995; border: 1px solid rgba(30,142,62,0.3); }
    .bg-update  { background-color: rgba(26,115,232,0.2);  color: #8ab4f8; border: 1px solid rgba(26,115,232,0.3); }
    .bg-delete  { background-color: rgba(217,48,37,0.2);   color: #f28b82; border: 1px solid rgba(217,48,37,0.3); }
    .bg-view    { background-color: rgba(242,153,0,0.2);   color: #fde293; border: 1px solid rgba(242,153,0,0.3); }
    .bg-default { background-color: rgba(154,160,166,0.2); color: #e8eaed; border: 1px solid rgba(154,160,166,0.3); }

    /* Movie Gallery */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
    }

    .movie-card {
        background: #0b0b14;
        border: 1px solid #2d2d3d;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.2s ease, border-color 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }
    .movie-card:hover { transform: translateY(-4px); border-color: #5c63ff; }

    .movie-card-poster {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }
    .movie-card-poster-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #1f1f32, #0b0b14);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
    }
    .movie-card-body { padding: 10px 12px; }
    .movie-card-title { font-size: 12px; font-weight: 600; color: #e8eaed; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .movie-card-meta  { font-size: 11px; color: #9aa0a6; }
    .avail-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #81c995; margin-right: 4px; }

    .overdue-banner { background: rgba(217,48,37,0.15); border: 1px solid rgba(217,48,37,0.3); border-radius: 10px; padding: 12px 20px; color: #f28b82; font-size: 13px; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; }
</style>

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
        <h3 class="fw-bold m-0" style="color:#e8eaed; font-size:20px;">Dashboard</h3>
        @role('Admin')
        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary btn-sm fw-semibold px-3">📊 View Reports</a>
        @endrole
    </div>

    {{-- Overdue warning --}}
    @if($overdueCount > 0)
    <div class="overdue-banner">
        ⚠️ <span>{{ $overdueCount }} rental{{ $overdueCount > 1 ? 's are' : ' is' }} overdue!</span>
        <a href="{{ route('rentals.index') }}" class="ms-2 text-danger fw-semibold text-decoration-none" style="font-size:12px;">View Rentals →</a>
    </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="dashboard-card">
            <div class="icon-block icon-blue">🎬</div>
            <div class="stat-text-container">
                <div class="stat-label">Total Movies</div>
                <div class="stat-value">{{ $stats['movies'] ?? 0 }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-block icon-green">📼</div>
            <div class="stat-text-container">
                <div class="stat-label">Physical Stock</div>
                <div class="stat-value">{{ $stats['tapes'] ?? 0 }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-block icon-teal">✅</div>
            <div class="stat-text-container">
                <div class="stat-label">Available Copies</div>
                <div class="stat-value">{{ $stats['available_tapes'] ?? 0 }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-block icon-orange">📤</div>
            <div class="stat-text-container">
                <div class="stat-label">Currently Rented</div>
                <div class="stat-value">{{ $stats['rented_tapes'] ?? 0 }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-block icon-yellow">🎭</div>
            <div class="stat-text-container">
                <div class="stat-label">Talent Registry</div>
                <div class="stat-value">{{ $stats['actors'] ?? 0 }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-block icon-red">📁</div>
            <div class="stat-text-container">
                <div class="stat-label">Categories</div>
                <div class="stat-value">{{ $stats['categories'] ?? 0 }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon-block icon-purple">👥</div>
            <div class="stat-text-container">
                <div class="stat-label">System Users</div>
                <div class="stat-value">{{ $stats['users'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    {{-- Movie Gallery --}}
    @if($availableMovies->isNotEmpty())
    <div class="panel-container mb-4">
        <div class="section-title">
            🎬 Movies Available for Rent
            <a href="{{ route('movies.index') }}" class="ms-auto text-muted small text-decoration-none fw-normal" style="font-size:12px;">View All →</a>
        </div>
        <div class="gallery-grid">
            @foreach($availableMovies as $movie)
            <div class="movie-card position-relative">
                <a href="{{ route('movies.show', $movie) }}" class="text-decoration-none" style="display:block;">
                    @if($movie->image)
                        <img src="{{ asset('storage/' . $movie->image) }}" class="movie-card-poster" alt="{{ $movie->title }}">
                    @else
                        <div class="movie-card-poster-placeholder">🎬</div>
                    @endif
                    <div class="movie-card-body">
                        <div class="movie-card-title" title="{{ $movie->title }}">{{ $movie->title }}</div>
                        <div class="movie-card-meta">
                            <span class="avail-dot"></span>{{ $movie->available_tapes_count }} available
                        </div>
                        <div class="movie-card-meta text-muted">{{ $movie->category->name }}</div>
                    </div>
                </a>
                <a href="{{ route('movies.rent', $movie) }}" class="btn btn-primary btn-sm position-absolute top-0 end-0 m-2 shadow fw-bold" style="font-size: 11px; z-index: 10; padding: 4px 10px;">Rent</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent Activity --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="panel-container">
                <div class="section-title">
                    <i class="bi bi-list-ul"></i> Recent Activity
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Target Type</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                                <tr>
                                    <td class="text-white">{{ $log->user_name ?? 'System' }}</td>
                                    <td>
                                        @if($log->action === 'CREATE')
                                            <span class="badge-action bg-create">Create</span>
                                        @elseif($log->action === 'UPDATE')
                                            <span class="badge-action bg-update">Update</span>
                                        @elseif($log->action === 'DELETE')
                                            <span class="badge-action bg-delete">Delete</span>
                                        @elseif($log->action === 'VIEW')
                                            <span class="badge-action bg-view">View</span>
                                        @else
                                            <span class="badge-action bg-default">{{ ucfirst(strtolower($log->action)) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            if ($log->model_type) {
                                                $modelName = class_basename($log->model_type);
                                                echo Str::plural(strtolower($modelName));
                                            } else {
                                                echo 'System / Core';
                                            }
                                        @endphp
                                    </td>
                                    <td>{{ $log->created_at ? $log->created_at->diffForHumans() : 'Just now' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No recent activity found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection