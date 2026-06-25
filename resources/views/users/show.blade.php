@extends('layouts.app')

@section('title', 'User Profile - ' . $user->name)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }
    .custom-card { border: 1px solid #2d2d3d; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); background: #161625; overflow: hidden; }
    .custom-card-header { background: #0b0b14; color: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #2d2d3d; }
    .custom-card-header h4 { margin: 0; font-weight: 600; font-size: 1.25rem; color: #f8f9fa; }
    .custom-card-header p { margin: 4px 0 0; font-size: 13px; color: #9fa0a6; }
    
    .info-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8b92; font-weight: 700; margin-bottom: 4px; }
    .info-value { font-size: 15px; color: #f8f9fa; margin-bottom: 1.5rem; }
    .border-dark-custom { border-color: #2d2d3d !important; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card col-lg-8 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>👤 User Identity Card</h4>
                <p>System credentials and administrative access parameters</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Return to Directory</a>
        </div>
        
        <div class="card-body p-4 row">
            
            <div class="col-md-6">
                <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Account Properties</h5>
                
                <div class="info-label">Full Name</div>
                <div class="info-value fw-semibold text-white fs-5">{{ $user->name }}</div>
                
                <div class="info-label">Email Address</div>
                <div class="info-value font-monospace text-info">{{ $user->email }}</div>

                <div class="info-label">Primary Role</div>
                <div class="info-value">
                    @if($user->roles->count() > 0)
                        <span class="badge bg-primary bg-opacity-20 text-white border border-primary border-opacity-30 rounded-pill px-3 py-1.5 fs-7">
                            {{ $user->roles->first()->name }}
                        </span>
                    @else
                        <span class="badge bg-secondary bg-opacity-20 text-light opacity-75 border border-secondary border-opacity-30 rounded-pill px-3 py-1.5 fs-7">
                            Unassigned
                        </span>
                    @endif
                </div>

                <div class="d-grid gap-2 mt-5 pt-3 border-top border-dark-custom">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary py-2 fw-semibold">Edit User Capabilities</a>
                    
                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Permanently revoke this user account?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-semibold" {{ auth()->id() == $user->id ? 'disabled' : '' }}>
                            {{ auth()->id() == $user->id ? 'Action Disabled (Self)' : 'Delete Account' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-6 border-start border-dark-custom ps-md-4 mt-4 mt-md-0">
                <h5 class="mb-4 pb-2 border-bottom border-dark-custom fw-semibold text-light">Inherited Permissions</h5>
                
                <div class="p-3 rounded border border-dark-custom" style="background-color: #0b0b14; min-height: 250px;">
                    <p class="text-light opacity-75 small mb-3 border-bottom border-secondary border-opacity-25 pb-2">Capabilities inherited through assigned roles:</p>
                    
                    @php
                        $permissions = $user->getAllPermissions();
                    @endphp

                    @forelse($permissions as $permission)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 rounded-pill px-2.5 py-1 m-1">
                            ✓ {{ $permission->name }}
                        </span>
                    @empty
                        <p class="text-light opacity-75 small mt-4 text-center">This user currently holds no explicit system permissions.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection