@extends('layouts.tourist-dashboard')

@section('title', 'Detail Pemesanan')

@section('content')
    <div class="container-fluid">
        <!-- Back Button -->
        <a href="{{ route('tourist.bookings.index') }}" class="mb-4 btn btn-link text-decoration-none">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pemesanan
        </a>

        <div class="row">
            <!-- Booking Details -->
            <div class="col-lg-8">
                <div class="mb-4 card">
                    <div class="bg-white card-header">
                        <h5 class="mb-0 card-title">Detail Pemesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4 row">
                            <div class="col-md-3">
                                <img src="{{ asset('storage/' . $booking->travelPackage->featured_image) }}"
                                    class="rounded img-fluid" alt="{{ $booking->travelPackage->name }}">
                            </div>
                            <div class="col-md-9">
                                <h5>{{ $booking->travelPackage->name }}</h5>
                                <p class="mb-1 text-muted">
                                    Kode Booking: {{ $booking->booking_code }}
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-calendar me-2"></i>
                                    {{ $booking->booking_date->format('d M Y') }}
                                </p>
                                <p class="mb-0">
                                    <i class="bi bi-people me-2"></i>
                                    {{ $booking->travelPackage->duration }}
                                </p>
                            </div>
                        </div>

                        <hr>

                        <!-- Status Timeline -->
                        <div class="mb-4 booking-timeline">
                            <div class="d-flex justify-content-between">
                                <div class="timeline-item {{ $booking->booking_status != 'cancelled' ? 'active' : '' }}">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <h6>Pemesanan</h6>
                                        <small>{{ $booking->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                </div>
                                <div class="timeline-item {{ $booking->payment_status == 'paid' ? 'active' : '' }}">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <h6>Pembayaran</h6>
                                        <small>{{ $booking->payment_status == 'paid' ? $booking->updated_at->format('d M Y H:i') : '-' }}</small>
                                    </div>
                                </div>
                                <div class="timeline-item {{ $booking->booking_status == 'confirmed' ? 'active' : '' }}">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <h6>Konfirmasi</h6>
                                        <small>{{ $booking->booking_status == 'confirmed' ? $booking->updated_at->format('d M Y H:i') : '-' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Details -->
                        <div class="price-details">
                            <h6 class="mb-3">Rincian Harga</h6>
                            <div class="p-4 rounded bg-light">
                                <!-- Package Price -->
                                <div class="mb-2 d-flex justify-content-between">
                                    <span>Harga Paket</span>
                                    <span>Rp {{ number_format($booking->travelPackage->price, 0, ',', '.') }}</span>
                                </div>

                                <!-- Quantity -->
                                <div class="mb-2 d-flex justify-content-between">
                                    <span>Jumlah Peserta</span>
                                    <span>{{ $booking->quantity }} orang</span>
                                </div>

                                <!-- Subtotal -->
                                <div class="mb-2 d-flex justify-content-between text-muted">
                                    <span>Subtotal</span>
                                    <span>Rp
                                        {{ number_format($booking->travelPackage->price * $booking->quantity, 0, ',', '.') }}</span>
                                </div>

                                @if ($booking->travelPackage->tax_percentage)
                                    <!-- Tax -->
                                    <div class="mb-2 d-flex justify-content-between text-muted">
                                        <span>Pajak ({{ $booking->travelPackage->tax_percentage }}%)</span>
                                        <span>Rp
                                            {{ number_format($booking->total_price - $booking->travelPackage->price * $booking->quantity, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                <hr class="my-3">

                                <!-- Total -->
                                <div class="d-flex justify-content-between">
                                    <strong>Total Pembayaran</strong>
                                    <strong class="text-primary">Rp
                                        {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                                </div>

                                <!-- Payment Status -->
                                <div class="mt-3 text-center">
                                    <span
                                        class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }} px-3 py-2">
                                        {{ $booking->payment_status === 'paid' ? 'Sudah Dibayar' : 'Menunggu Pembayaran' }}
                                    </span>
                                </div>
                            </div>

                            @if ($booking->payment_status === 'unpaid')
                                <!-- Payment Due -->
                                <div class="mt-3">
                                    <small class="text-danger">
                                        <i class="bi bi-clock me-1"></i>
                                        Harap selesaikan pembayaran dalam waktu 24 jam
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="col-lg-4">
                @if ($booking->payment_status == 'unpaid')
                    <div class="mb-4 card">
                        <div class="bg-white card-header">
                            <h5 class="mb-0 card-title">Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <!-- Payment Instructions -->
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Instruksi Pembayaran:</h6>
                                <p class="mb-0">Silakan transfer ke rekening berikut:</p>
                                <hr>
                                <p class="mb-1">Bank BCA</p>
                                <p class="mb-1">1234567890</p>
                                <p class="mb-0">a.n. PT Wisata Muna</p>
                            </div>

                            <!-- Upload Payment Proof -->
                            <form action="{{ route('tourist.bookings.upload-payment', $booking) }}" method="POST"
                                enctype="multipart/form-data" class="mt-4">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Upload Bukti Pembayaran</label>
                                    <input type="file" class="form-control @error('payment_proof') is-invalid @enderror"
                                        name="payment_proof" accept="image/*" required>
                                    @error('payment_proof')
                                        <div class="invalid-feedback">
                                            {{ $message ?? 'Bukti pembayaran wajib diunggah dan harus berupa gambar.' }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        Format yang diterima: JPG, PNG (Maks. 2MB)
                                    </div>
                                </div>

                                <!-- Preview Image -->
                                <div class="mb-3 d-none" id="imagePreview">
                                    <img src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-upload me-2"></i>
                                    Upload Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Contact Info -->
                <div class="border-0 shadow-sm card">
                    <div class="bg-white border-0 card-header">
                        <h5 class="mb-0 card-title">Informasi Kontak</h5>
                    </div>
                    <div class="card-body">
                        <!-- Contact Details -->
                        <div class="mb-4">
                            <!-- District Info -->
                            @if ($booking->travelPackage->district)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="p-2 bg-light rounded-circle">
                                                <i class="bi bi-geo-alt text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <small class="text-muted d-block">Lokasi</small>
                                            <span>{{ $booking->travelPackage->district->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Duration -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-light rounded-circle">
                                            <i class="bi bi-clock text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted d-block">Durasi</small>
                                        <span>{{ $booking->travelPackage->duration }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Meeting Point -->
                            @if ($booking->travelPackage->meeting_point)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="p-2 bg-light rounded-circle">
                                                <i class="bi bi-pin-map text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <small class="text-muted d-block">Titik Kumpul</small>
                                            <span>{{ $booking->travelPackage->meeting_point }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Contact Buttons -->
                        <div class="gap-2 d-grid">
                            <!-- Customer Service -->
                            <a href="https://wa.me/6282346338821" class="btn btn-success" target="_blank">
                                <i class="bi bi-whatsapp me-2"></i>
                                Hubungi Customer Service
                            </a>

                            <!-- Help Center -->
                            <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                                <i class="bi bi-question-circle me-2"></i>
                                Pusat Bantuan
                            </a>
                        </div>

                        <!-- Additional Info -->
                        <div class="mt-4">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Butuh bantuan? Customer service kami siap membantu 24/7
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Timeline Styling */
            .booking-timeline {
                position: relative;
                padding: 20px 0;
            }

            .booking-timeline::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 2px;
                background: #e2e8f0;
                z-index: 1;
            }

            .timeline-item {
                position: relative;
                z-index: 2;
                text-align: center;
                background: white;
            }

            .timeline-point {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: #e2e8f0;
                margin: 0 auto 10px;
                border: 4px solid white;
            }

            .timeline-item.active .timeline-point {
                background: var(--primary-color);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Preview image before upload
            document.querySelector('input[name="payment_proof"]').addEventListener('change', function(e) {
                const preview = document.getElementById('imagePreview');
                const file = e.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.querySelector('img').src = e.target.result;
                        preview.classList.remove('d-none');
                    }

                    reader.readAsDataURL(file);
                } else {
                    preview.classList.add('d-none');
                }
            });
        </script>
    @endpush
@endsection
