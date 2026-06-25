@extends('layouts.app')

@section('title', 'Modify User - ' . $user->name)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #0f0f15; color: #e4e6eb; }
    .custom-card { border: 1px solid #2d2d3d; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); background: #161625; overflow: hidden; }
    .custom-card-header { background: #0b0b14; color: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #2d2d3d; }
    .custom-card-header h4 { margin: 0; font-weight: 600; font-size: 1.25rem; color: #f8f9fa; }
    .custom-card-header p { margin: 4px 0 0; font-size: 13px; color: #9fa0a6; }
    
    .form-label-custom { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8b92; font-weight: 700; margin-bottom: 6px; }
    
    .form-control-custom { background-color: #222235 !important; border: 1px solid #3a3a52 !important; color: #ffffff !important; border-radius: 8px; padding: 10px 14px; }
    .form-control-custom:focus { background-color: #2a2a3f !important; border-color: #5c63ff !important; color: #fff !important; box-shadow: none; }
    .form-control-custom::placeholder { color: #a1a1aa !important; opacity: 1 !important; }
    
    .text-danger-custom { color: #ff6b6b; font-size: 12px; margin-top: 4px; display: block; }
    .text-white-custom { color: #ffffff !important; background-color: #222235 !important; }
</style>

<div class="container-fluid py-4">
    <div class="custom-card col-lg-7 mx-auto">
        <div class="custom-card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>📝 Update User Credentials</h4>
                <p>Modifying access profile for: {{ $user->name }}</p>
            </div>
            <a href="{{ Str::contains(url()->previous(), route('users.show', $user)) ? route('users.show', $user) : route('users.index') }}" class="btn btn-outline-light btn-sm fw-semibold px-3">← Cancel</a>
        </div>
        
        <div class="card-body p-4">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label-custom">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control form-control-custom @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name') <span class="text-danger-custom">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label-custom">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control form-control-custom @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email') <span class="text-danger-custom">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="p-3 border border-dark-custom rounded" style="background-color: #0b0b14;">
                            <p class="text-light opacity-75 small mb-3">⚠️ Leave password fields blank if you do not wish to change the current password.</p>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="password" class="form-label-custom">New Password</label>
                                    <input type="password" name="password" id="password" class="form-control form-control-custom @error('password') is-invalid @enderror" placeholder="Enter new password">
                                    @error('password') <span class="text-danger-custom">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label-custom">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-custom" placeholder="Re-type new password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="role" class="form-label-custom">System Authorization Role</label>
                    @php $currentRole = $user->roles->first()->name ?? ''; @endphp
                    <select name="role" id="role" class="form-select form-control-custom text-white-custom @error('role') is-invalid @enderror" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" class="text-white-custom" {{ old('role', $currentRole) == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-danger-custom">{{ $message }}</span> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary border-opacity-10">
                    <a href="{{ Str::contains(url()->previous(), route('users.show', $user)) ? route('users.show', $user) : route('users.index') }}" class="btn btn-outline-danger px-4 fw-semibold">Abandon Edits</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">Save Access Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection