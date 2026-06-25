@extends('layouts.app')

@section('title', 'System User Management')

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
        color: #a1a1aa !important;
        opacity: 1 !important;
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

    .user-name-link {
        color: #38bdf8 !important;
        font-weight: 600;
        text-decoration: none;
        font-size: 15px;
    }

    .user-name-link:hover {
        color: #0d6efd !important;
        text-decoration: underline;
    }
</style>

<div class="container-fluid py-4">
    @if(session('error'))
    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 fw-semibold mb-4 col-lg-10 mx-auto text-center rounded-3">
        ⚠️ {{ session('error') }}
    </div>
    @endif

    <div class="custom-card col-lg-10 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>🔐 System Users & Roles</h4>
                <p>Manage access credentials and administrative privileges</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-light btn-sm fw-semibold px-3">+ Add New User</a>
        </div>

        <div class="card-body p-4">
            <form method="GET" action="{{ route('users.index') }}" class="row g-2 mb-4">
                <div class="col-md-9">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control dark-input h-100" placeholder="Search users by name or email address...">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 h-100 fw-semibold" style="font-size: 13px;">Search Accounts</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-light w-100 h-100 fw-semibold d-flex align-items-center justify-content-center text-decoration-none" style="font-size: 13px;">Reset</a>
                </div>
            </form>

            <div class="table-responsive" style="border-radius: 8px; overflow: hidden; border: 1px solid #2d2d3d;">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="25%">Full Name</th>
                            <th width="35%">Email Address</th>
                            <th width="20%">Assigned Role</th>
                            <th width="20%" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('users.show', $user) }}" class="user-name-link">
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td><span class="text-light opacity-75 font-monospace">{{ $user->email }}</span></td>
                            <td>
                                @if($user->roles->count() > 0)
                                <span class="badge bg-primary bg-opacity-20 text-white border border-primary border-opacity-30 rounded-pill px-3 py-1">
                                    {{ $user->roles->first()->name }}
                                </span>
                                @else
                                <span class="badge bg-secondary bg-opacity-20 text-light opacity-75 border border-secondary border-opacity-30 rounded-pill px-3 py-1">
                                    No Role
                                </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-light btn-sm px-2.5">View</a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary btn-sm px-2.5">Edit</a>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Permanently delete this user account? Access will be revoked immediately.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-2.5" {{ auth()->id() == $user->id ? 'disabled' : '' }}>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-light opacity-75 py-4">No system users match your search query.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection