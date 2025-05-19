<section class="travel-packages py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Paket Wisata Terbaik</h2>
            <a href="{{ route('packages.index') }}" class="btn btn-link text-primary">Lihat Semua</a>
        </div>

        <div class="row g-4">
            @foreach(\App\Models\TravelPackage::where('is_featured', true)->limit(3)->get() as $package)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 package-card">
                        <div class="position-relative">
                            @if($package->featured_image)
                                <img src="{{ asset('storage/' . $package->featured_image) }}" class="card-img-top" alt="{{ $package->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light" style="height: 200px;"></div>
                            @endif
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-white text-primary">{{ $package->duration }} Hari</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $package->name }}</h5>
                            <div class="d-flex align-items-center mb-2 text-muted small">
                                <i class="bi bi-shield-check me-1"></i>
                                <span>{{ ucfirst($package->difficulty) }}</span>
                            </div>

                            <div class="mb-3">
                                @foreach($package->destinations()->take(3)->get() as $index => $destination)
                                    <span class="badge bg-light text-dark me-1">{{ $destination->name }}</span>
                                    @if($index === 2 && $package->destinations()->count() > 3)
                                        <span class="badge bg-secondary">+{{ $package->destinations()->count() - 3 }}</span>
                                    @endif
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <div>
                                    <small class="text-muted d-block">Mulai dari</small>
                                    <span class="text-primary fw-bold">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('packages.show', $package->slug) }}" class="btn btn-primary">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
