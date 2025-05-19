<section class="search-box-wrapper">
    <div class="container">
        <div class="bg-white shadow-lg search-box rounded-4">
            <ul class="nav nav-tabs" id="searchTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="destination-tab" data-bs-toggle="tab"
                        data-bs-target="#destination" type="button" role="tab">Destinasi</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="package-tab" data-bs-toggle="tab" data-bs-target="#package"
                        type="button" role="tab">Paket Wisata</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="event-tab" data-bs-toggle="tab" data-bs-target="#event" type="button"
                        role="tab">Event</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="accommodation-tab" data-bs-toggle="tab" data-bs-target="#accommodation"
                        type="button" role="tab">Akomodasi</button>
                </li>
            </ul>
            <div class="pt-3 tab-content" id="searchTabsContent">
                <!-- Destination Search Tab -->
                <div class="tab-pane fade show active" id="destination" role="tabpanel">
                    <form action="{{ url('/destinations') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="text" name="q" class="form-control"
                                    placeholder="Cari destinasi...">
                            </div>
                            <div class="col-md-4">
                                <select name="district_id" class="form-select">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach (\App\Models\District::orderBy('name')->get() as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Cari Destinasi</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Package Search Tab -->
                <div class="tab-pane fade" id="package" role="tabpanel">
                    <form action="{{ url('/packages') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="text" name="q" class="form-control"
                                    placeholder="Cari paket wisata...">
                            </div>
                            <div class="col-md-4">
                                <select name="duration" class="form-select">
                                    <option value="">Semua Durasi</option>
                                    <option value="1">1 Hari</option>
                                    <option value="2">2 Hari</option>
                                    <option value="3">3 Hari</option>
                                    <option value="4+">4+ Hari</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Cari Paket</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Event Search Tab -->
                <div class="tab-pane fade" id="event" role="tabpanel">
                    <form action="{{ url('/events') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="text" name="q" class="form-control" placeholder="Cari event...">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Cari Event</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Accommodation Search Tab -->
                <div class="tab-pane fade" id="accommodation" role="tabpanel">
                    <form action="{{ url('/accommodations') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="text" name="q" class="form-control"
                                    placeholder="Cari akomodasi...">
                            </div>
                            <div class="col-md-4">
                                <select name="type" class="form-select">
                                    <option value="">Semua Tipe</option>
                                    <option value="hotel">Hotel</option>
                                    <option value="homestay">Homestay</option>
                                    <option value="villa">Villa</option>
                                    <option value="guesthouse">Guesthouse</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Cari Akomodasi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        .search-box-wrapper {
            position: relative;
            z-index: 10;
        }

        .search-box {
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .search-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }

        .nav-tabs {
            border: none;
            margin-bottom: 1rem;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: var(--bs-primary);
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            background: transparent;
            border-bottom: 3px solid var(--bs-primary);
        }

        @media (max-width: 768px) {
            .search-box {
                margin: 0 1rem;
                padding: 1rem;
            }

            .nav-tabs .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
        }
    </style>
@endpush
