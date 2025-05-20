@extends('layouts.tourist-dashboard')

@section('title', 'Pemesanan Saya')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Pemesanan Saya</h4>
        <div class="btn-group">
            <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                Filter Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item {{ request('status') == '' ? 'active' : '' }}"
                       href="{{ route('tourist.bookings.index') }}">Semua</a></li>
                <li><a class="dropdown-item {{ request('status') == 'pending' ? 'active' : '' }}"
                       href="{{ route('tourist.bookings.index', ['status' => 'pending']) }}">Menunggu Pembayaran</a></li>
                <li><a class="dropdown-item {{ request('status') == 'confirmed' ? 'active' : '' }}"
                       href="{{ route('tourist.bookings.index', ['status' => 'confirmed']) }}">Terkonfirmasi</a></li>
                <li><a class="dropdown-item {{ request('status') == 'completed' ? 'active' : '' }}"
                       href="{{ route('tourist.bookings.index', ['status' => 'completed']) }}">Selesai</a></li>
                <li><a class="dropdown-item {{ request('status') == 'cancelled' ? 'active' : '' }}"
                       href="{{ route('tourist.bookings.index', ['status' => 'cancelled']) }}">Dibatalkan</a></li>
            </ul>
        </div>
    </div>

    <!-- Booking List -->
    <div class="row g-3">
        @forelse($bookings as $booking)
        <div class="col-12">
            <div class="border-0 shadow-sm card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Package Image -->
                        <div class="col-md-2">
                            <img src="{{ asset('storage/' . $booking->travelPackage->featured_image) }}"
                                 class="rounded img-fluid"
                                 style="object-fit: cover; height: 100px; width: 100%;"
                                 alt="{{ $booking->travelPackage->name }}">
                        </div>

                        <!-- Booking Details -->
                        <div class="col-md-7">
                            <h5 class="mb-1 card-title">{{ $booking->travelPackage->name }}</h5>
                            <p class="mb-1 text-muted">
                                <i class="bi bi-ticket-perforated me-2"></i>
                                Kode Booking: {{ $booking->booking_code }}
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-calendar me-2"></i>
                                {{ $booking->booking_date->format('d M Y') }}
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-cash me-2"></i>
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Status & Actions -->
                        <div class="col-md-3 text-md-end">
                            <span class="badge bg-{{ $booking->booking_status === 'confirmed' ? 'success' : ($booking->booking_status === 'pending' ? 'warning' : 'danger') }} mb-2">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                            <br>
                            <div class="btn-group">
                                <a href="{{ route('tourist.bookings.show', $booking) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye me-1"></i>
                                    Detail
                                </a>

                                @if($booking->booking_status === 'confirmed')
                                    <a href="{{ route('tourist.bookings.download-ticket', $booking) }}"
                                       class="btn btn-sm btn-success">
                                        <i class="bi bi-download me-1"></i>
                                        Tiket
                                    </a>
                                @endif

                                @if($booking->booking_status === 'pending')
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmCancel('{{ $booking->id }}')">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Batalkan
                                    </button>
                                    <form id="cancel-form-{{ $booking->id }}"
                                          action="{{ route('tourist.bookings.cancel', $booking) }}"
                                          method="POST"
                                          class="d-none">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="py-5 text-center">
                <img src="{{ asset('images/empty-booking.svg') }}"
                     alt="No Bookings"
                     class="mb-3"
                     style="max-width: 200px">
                <h5>Belum ada pemesanan</h5>
                <p class="text-muted">
                    Jelajahi paket wisata menarik dan mulai petualangan Anda!
                </p>
                <a href="{{ route('packages.index') }}" class="btn btn-primary">
                    <i class="bi bi-compass me-2"></i>
                    Lihat Paket Wisata
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>

@push('scripts')
<script>
function confirmCancel(bookingId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')) {
        document.getElementById('cancel-form-' + bookingId).submit();
    }
}
</script>
@endpush
@endsection
