@extends('layouts.app')

@section('title', 'Role & Permission Management')

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

    .role-name-link {
        color: #a855f7 !important;
        font-weight: 600;
        text-decoration: none;
        font-size: 15px;
    }

    .role-name-link:hover {
        color: #c084fc !important;
        text-decoration: underline;
    }

    .stat-badge {
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
    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 fw-semibold mb-4 col-lg-10 mx-auto text-center rounded-3">
        ⚠️ {{ session('error') }}
    </div>
    @endif

    <div class="custom-card col-lg-10 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>🛡️ System Roles & Permissions</h4>
                <p>Manage authorization levels and map capabilities to specific groups</p>
            </div>
            <a href="{{ route('roles.create') }}" class="btn btn-light btn-sm fw-semibold px-3">+ Create New Role</a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="30%" class="ps-4">Role Classification Name</th>
                            <th width="25%">Active Users</th>
                            <th width="25%">Permissions Granted</th>
                            <th width="20%" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('roles.show', $role) }}" class="role-name-link">
                                    {{ $role->name }}
                                </a>
                            </td>
                            <td>
                                <span class="stat-badge">👥 {{ $role->users_count }} Assigned</span>
                            </td>
                            <td>
                                <span class="badge bg-success border border-success border-opacity-50 rounded-pill px-3 py-1 text-white fw-medium">
                                    {{ $role->permissions->count() }} Capabilities
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-light btn-sm px-2.5">View</a>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary btn-sm px-2.5">Edit</a>
                                    <form method="POST" action="{{ route('roles.destroy', $role) }}" onsubmit="return confirm('Delete this role? Users assigned to this role will lose these permissions.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-2.5" {{ $role->name === 'Admin' ? 'disabled' : '' }}>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-light opacity-75 py-4">No roles are currently configured in the system.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection