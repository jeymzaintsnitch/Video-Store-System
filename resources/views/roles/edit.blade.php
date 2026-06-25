@extends('layouts.app')

@section('title', 'Modify Role - ' . $role->name)

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

    .form-label-custom {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #8a8b92;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .form-control-custom {
        background-color: #222235 !important;
        border: 1px solid #3a3a52 !important;
        color: #ffffff !important;
        border-radius: 8px;
        padding: 10px 14px;
    }

    .form-control-custom:focus {
        background-color: #2a2a3f !important;
        border-color: #5c63ff !important;
        color: #fff !important;
        box-shadow: none;
    }

    .border-dark-custom {
        border-color: #2d2d3d !important;
    }

    .form-check-input-custom {
        background-color: #222235;
        border: 1px solid #3a3a52;
        cursor: pointer;
    }

    .form-check-input-custom:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .form-check-input-custom:focus {
        box-shadow: none;
        border-color: #5a5b7a;
    }

    .clickable-label {
        cursor: pointer;
        user-select: none;
    }

    .text-danger-custom {
        color: #ff6b6b;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }
</style>

<div class="container-fluid py-4">
    <div class="custom-card col-lg-8 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📝 Update Role Parameters</h4>
                <p>Modifying capabilities for: {{ $role->name }}</p>
            </div>
            <a href="{{ Str::contains(url()->previous(), route('roles.show', $role)) ? route('roles.show', $role) : route('roles.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Cancel</a>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('roles.update', $role) }}">
                @csrf
                @method('PUT')

                <div class="mb-4 col-md-6">
                    <label for="name" class="form-label-custom">Role Identification Name</label>
                    <input type="text" name="name" id="name" class="form-control form-control-custom @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
                    @error('name') <span class="text-danger-custom">{{ $message }}</span> @enderror
                </div>

                <h5 class="mb-3 mt-5 pb-2 border-bottom border-dark-custom fw-semibold text-light">Modify Privileges & Capabilities</h5>

                <div class="row">
                    @foreach($permissions as $group => $perms)
                    <div class="col-md-4 mb-4">
                        <div class="p-3 border border-dark-custom rounded h-100" style="background-color: #0b0b14;">
                            <h6 class="text-white text-capitalize mb-3 border-bottom border-secondary border-opacity-25 pb-2">{{ $group }} Module</h6>

                            @foreach($perms as $permission)
                            <div class="form-check mb-2">
                                <input class="form-check-input form-check-input-custom" type="checkbox" name="permission_ids[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                    {{ in_array($permission->id, old('permission_ids', $rolePermissionIds)) ? 'checked' : '' }}>
                                <label class="form-check-label text-light opacity-75 small clickable-label" for="perm_{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                @error('permission_ids') <span class="text-danger-custom mb-3">{{ $message }}</span> @enderror

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-dark-custom">
                    <a href="{{ Str::contains(url()->previous(), route('roles.show', $role)) ? route('roles.show', $role) : route('roles.index') }}" class="btn btn-outline-danger px-4 fw-semibold">Abandon Edits</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">Save Framework Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection