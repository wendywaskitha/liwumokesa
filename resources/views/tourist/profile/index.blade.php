@extends('layouts.tourist-dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="text-center card-body">
                    <div class="mb-4 position-relative">
                        <img src="{{ auth()->user()->profile_photo_url }}"
                             alt="{{ auth()->user()->name }}"
                             class="rounded-circle img-thumbnail"
                             style="width: 150px; height: 150px; object-fit: cover;">

                        <button type="button"
                                class="bottom-0 btn btn-sm btn-primary position-absolute end-0"
                                data-bs-toggle="modal"
                                data-bs-target="#updatePhotoModal">
                            <i class="bi bi-camera"></i>
                        </button>
                    </div>

                    <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                    <p class="text-muted small">
                        <i class="bi bi-envelope me-1"></i>{{ auth()->user()->email }}
                    </p>

                    <div class="d-grid">
                        <button type="button"
                                class="btn btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil me-1"></i>Edit Profil
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 card-title">Informasi Dasar</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-md-4 text-muted">Nama Lengkap</div>
                        <div class="col-md-8">{{ auth()->user()->name }}</div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-4 text-muted">Email</div>
                        <div class="col-md-8">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-4 text-muted">No. Telepon</div>
                        <div class="col-md-8">{{ auth()->user()->phone_number ?? '-' }}</div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-4 text-muted">Alamat</div>
                        <div class="col-md-8">{{ auth()->user()->address ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 text-muted">Bergabung Sejak</div>
                        <div class="col-md-8">{{ auth()->user()->created_at->format('d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('tourist.profile.partials.edit-modal')
@include('tourist.profile.partials.update-photo-modal')
@endsection
