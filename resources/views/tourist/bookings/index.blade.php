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
                <li><a class="dropdown-item" href="#">Semua</a></li>
                <li><a class="dropdown-item" href="#">Menunggu Pembayaran</a></li>
                <li><a class="dropdown-item" href="#">Terkonfirmasi</a></li>
                <li><a class="dropdown-item" href="#">Selesai</a></li>
                <li><a class="dropdown-item" href="#">Dibatalkan</a></li>
            </ul>
        </div>
    </div>

    <!-- Booking List -->
    <div class="row g-3">
        @forelse($bookings as $booking)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Package Image -->
                        <div class="col-md-2">
                            <img src="{{ asset('storage/' . $booking->travelPackage->image) }}"
                                 class="rounded img-fluid"
                                 alt="{{ $booking->travelPackage->name }}">
                        </div>

                        <!-- Booking Details -->
                        <div class="col-md-7">
                            <h5 class="mb-1 card-title">{{ $booking->travelPackage->name }}</h5>
                            <p class="mb-1 text-muted">
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
                                    Detail
                                </a>
                                @if($booking->booking_status === 'pending')
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmCancel('{{ $booking->id }}')">
                                        Batalkan
                                    </button>
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
