<section class="about-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-4">Tentang Muna Barat</h2>
                <div class="mb-4">
                    {!! \App\Models\Setting::get('website.about_section', '<p>Kabupaten Muna Barat adalah salah satu kabupaten di Provinsi Sulawesi Tenggara, Indonesia yang memiliki beragam destinasi wisata menarik.</p>') !!}
                </div>

                <div class="row mt-5">
                    <div class="col-md-3 col-6 mb-4">
                        <div class="counter-item">
                            <div class="h2 text-primary fw-bold">11</div>
                            <p class="text-muted">Kecamatan</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="counter-item">
                            <div class="h2 text-primary fw-bold">{{ \App\Models\Destination::count() }}</div>
                            <p class="text-muted">Destinasi Wisata</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="counter-item">
                            <div class="h2 text-primary fw-bold">{{ \App\Models\CulturalHeritage::count() ?? 15 }}</div>
                            <p class="text-muted">Warisan Budaya</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="counter-item">
                            <div class="h2 text-primary fw-bold">{{ \App\Models\Accommodation::count() ?? 20 }}</div>
                            <p class="text-muted">Akomodasi</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('about') }}" class="btn btn-primary mt-3">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </div>
</section>
