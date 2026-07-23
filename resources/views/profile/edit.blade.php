@extends('layouts.app')

@section('title', 'Profile')

@push('styles')
<style>
    .profile-header-card {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border-radius: 16px;
        padding: 2rem;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .profile-header-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 700;
        flex-shrink: 0;
        object-fit: cover;
    }
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        height: 100%;
    }
    .profile-card .card-body {
        padding: 2rem;
    }
    .profile-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
    }
    .profile-card .form-label {
        font-weight: 500;
        font-size: 0.875rem;
        color: #374151;
    }
    .profile-card .form-control {
        border-radius: 10px;
        padding: 0.65rem 0.9rem;
        border-color: #e5e7eb;
    }
    .profile-card .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
    }
    .profile-card .btn-primary {
        border-radius: 10px;
        padding: 0.6rem 1.75rem;
        background: #2563eb;
        border: none;
        font-weight: 500;
    }
    .profile-card .btn-primary:hover {
        background: #1d4ed8;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <div class="profile-header-card">
        @if ($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil" class="profile-header-avatar">
        @else
            <div class="profile-header-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
            <div class="opacity-75">{{ $user->email }}</div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card profile-card">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card profile-card">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection