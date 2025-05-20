{{-- resources/views/tourist/bookings/partials/_order-summary.blade.php --}}

<div class="border-0 shadow-sm card">
    <div class="p-4 card-body">
        <h5 class="mb-4 card-title">Ringkasan Pesanan</h5>

        <!-- Package Details -->
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">{{ $booking->travelPackage->name }}</h6>
                    <p class="mb-0 text-muted">
                        <i class="bi bi-clock me-1"></i>
                        {{ $booking->travelPackage->duration }}
                    </p>
                </div>
                <span class="badge bg-primary">{{ $booking->booking_status }}</span>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="p-3 mb-4 rounded bg-light">
            <div class="mb-2 d-flex justify-content-between">
                <span>Tanggal Kunjungan</span>
                <span>{{ $booking->booking_date->format('d M Y') }}</span>
            </div>
            <div class="mb-2 d-flex justify-content-between">
                <span>Jumlah Peserta</span>
                <span>{{ $booking->quantity }} orang</span>
            </div>
            <div class="mb-0 d-flex justify-content-between">
                <span>Harga per orang</span>
                <span>Rp {{ number_format($booking->travelPackage->price, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Price Calculation -->
        <div class="mb-3">
            <div class="mb-2 d-flex justify-content-between">
                <span>Subtotal</span>
                <span>Rp {{ number_format($booking->travelPackage->price * $booking->quantity, 0, ',', '.') }}</span>
            </div>
            @if($booking->travelPackage->tax_percentage)
                <div class="mb-2 d-flex justify-content-between text-muted">
                    <span>Pajak ({{ $booking->travelPackage->tax_percentage }}%)</span>
                    <span>Rp {{ number_format($booking->total_price - ($booking->travelPackage->price * $booking->quantity), 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

        <hr>

        <!-- Total -->
        <div class="d-flex justify-content-between align-items-center">
            <span class="mb-0 h6">Total Pembayaran</span>
            <span class="mb-0 h5 text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>

        <!-- Package Inclusions -->
        @if($booking->travelPackage->inclusions)
            <div class="mt-4">
                <small class="mb-2 text-muted d-block">
                    <i class="bi bi-info-circle me-1"></i>
                    Termasuk dalam paket:
                </small>
                <ul class="mb-0 list-unstyled">
                    @foreach(array_slice($booking->travelPackage->inclusions, 0, 3) as $inclusion)
                        <li class="mb-1">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            {{ $inclusion }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
