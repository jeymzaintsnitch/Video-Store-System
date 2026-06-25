@extends('layouts.app')

@section('title', 'Genre & Classification Database')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #0f0f15;
        color: #e4e6eb;
    }

    .custom-card {
        border: 1px solid #2d2d3d;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        background: #161625;
        overflow: hidden;
    }

    .custom-card-header {
        background: #0b0b14;
        color: #fff;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #2d2d3d;
    }

    .custom-card-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.25rem;
        color: #f8f9fa;
    }

    .custom-card-header p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #9fa0a6;
    }

    .dark-input {
        background-color: #1f1f32 !important;
        border: 1px solid #3a3a52 !important;
        color: #fff !important;
    }

    .dark-input::placeholder {
        color: #787985;
    }

    .dark-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-color: #5c63ff;
    }

    .table-dark-custom {
        margin-bottom: 0;
        background-color: #161625 !important;
    }

    .table-dark-custom thead tr {
        background-color: #0b0b14 !important;
    }

    .table-dark-custom th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #8a8b92 !important;
        padding: 14px 16px;
        border-bottom: 1px solid #2d2d3d !important;
        background: transparent;
    }

    .table-dark-custom tbody tr {
        background-color: #161625 !important;
        transition: background-color 0.2s ease;
    }

    .table-dark-custom td {
        font-size: 13px;
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #222235 !important;
        color: #ffffff !important;
        background: transparent;
    }

    .table-dark-custom tbody tr:hover {
        background-color: #1f1f32 !important;
    }

    .category-name-link {
        color: #f43f5e !important;
        font-weight: 600;
        text-decoration: none;
        font-size: 15px;
    }

    .category-name-link:hover {
        color: #e11d48 !important;
        text-decoration: underline;
    }

    .movie-count-badge {
        background-color: #3f3f46;
        color: #e4e4e7;
        border: 1px solid #52525b;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }
</style>

<div class="container-fluid py-4">
    @if(session('error'))
    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 fw-semibold mb-4 col-lg-8 mx-auto text-center rounded-3">
        ⚠️ {{ session('error') }}
    </div>
    @endif

    <div class="custom-card col-lg-10 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📑 Genre & Classification</h4>
                <p>Manage system categories used to organize cinematic catalog entries</p>
            </div>
            @can('create categories')
            <a href="{{ route('categories.create') }}" class="btn btn-light btn-sm fw-semibold px-3">+ Add New Genre</a>
            @endcan
        </div>

        <div class="card-body p-4">
            <form method="GET" action="{{ route('categories.index') }}" class="row g-2 mb-4">
                <div class="col-md-9">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control dark-input h-100" placeholder="Search categories by name...">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 h-100 fw-semibold" style="font-size: 13px;">Search</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-light w-100 h-100 fw-semibold d-flex align-items-center justify-content-center text-decoration-none" style="font-size: 13px;">Reset</a>
                </div>
            </form>
            <div class="table-responsive" style="border-radius: 8px; overflow: hidden; border: 1px solid #2d2d3d;">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="25%">Genre / Category Name</th>
                            <th width="45%">Definition Description</th>
                            <th width="15%">Titles Assigned</th>
                            <th width="15%" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>
                                <a href="{{ route('categories.show', $category) }}" class="category-name-link">
                                    {{ $category->name }}
                                </a>
                            </td>
                            <td><span class="text-light opacity-75">{{ Str::limit($category->description, 70) ?? 'No description defined.' }}</span></td>
                            <td>
                                <span class="movie-count-badge">
                                    🎬 {{ $category->movies_count }} Movie(s)
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-light btn-sm px-2.5">View</a>

                                    @can('edit categories')
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary btn-sm px-2.5">Edit</a>
                                    @endcan

                                    @can('delete categories')
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this genre?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-2.5">Delete</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No genre categories match your current search query.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection