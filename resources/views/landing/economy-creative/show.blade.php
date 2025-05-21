@extends('layouts.landing')

@section('content')
    <div class="container py-5">
        <!-- Back Button -->
        <a href="{{ route('economy-creative.index') }}" class="mb-4 btn btn-link text-decoration-none">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar UMKM
        </a>

        <!-- Info Ekonomi Kreatif -->
        <div class="mb-4 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Featured Image with Pexels fallback -->
                        @php
                            $pexelsImages = [
                                'handicraft' => 'https://images.pexels.com/photos/4553036/pexels-photo-4553036.jpeg',
                                'fashion' => 'https://images.pexels.com/photos/3735641/pexels-photo-3735641.jpeg',
                                'food' => 'https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg',
                                'art' => 'https://images.pexels.com/photos/1509534/pexels-photo-1509534.jpeg',
                            ];
                            $defaultImage =
                                $pexelsImages[$creativeEconomy->category->slug] ??
                                'https://images.pexels.com/photos/3735641/pexels-photo-3735641.jpeg';
                        @endphp
                        <img src="{{ $creativeEconomy->featured_image ? Storage::url($creativeEconomy->featured_image) : $defaultImage }}"
                            class="rounded img-fluid" alt="{{ $creativeEconomy->name }}">

                        <!-- Gallery Section -->
                        {{-- <div class="mt-4">
                            <h5 class="mb-3">Galeri Foto</h5>
                            <div class="row g-2">
                                @if ($creativeEconomy->galleries->count() > 0)
                                    @foreach ($creativeEconomy->galleries->sortBy('order') as $gallery)
                                        <div class="col-6 col-md-4">
                                            <a href="javascript:;" class="d-block gallery-item"
                                                data-image="{{ Storage::url($gallery->file_path) }}"
                                                data-caption="{{ $gallery->caption }}">
                                                <img src="{{ Storage::url($gallery->file_path) }}"
                                                    class="rounded img-fluid w-100"
                                                    style="height: 150px; object-fit: cover;"
                                                    alt="{{ $gallery->caption ?? 'Gallery image ' . $loop->iteration }}">
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    @php
                                        $defaultGallery = [
                                            'https://images.pexels.com/photos/5709010/pexels-photo-5709010.jpeg',
                                            'https://images.pexels.com/photos/4348404/pexels-photo-4348404.jpeg',
                                            'https://images.pexels.com/photos/4348401/pexels-photo-4348401.jpeg',
                                        ];
                                    @endphp
                                    @foreach ($defaultGallery as $index => $image)
                                        <div class="col-6 col-md-4">
                                            <a href="javascript:;" class="d-block gallery-item"
                                                data-image="{{ $image }}"
                                                data-caption="Sample Gallery Image {{ $index + 1 }}">
                                                <img src="{{ $image }}" class="rounded img-fluid w-100"
                                                    style="height: 150px; object-fit: cover;"
                                                    alt="Sample Gallery Image {{ $index + 1 }}">
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div> --}}
                    </div>

                    <div class="col-md-8">
                        <!-- Header Info -->
                        <div class="mb-3 d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="mb-1">{{ $creativeEconomy->name }}</h2>
                                <p class="mb-2 text-muted">{{ $creativeEconomy->category->name }}</p>
                                @if ($creativeEconomy->is_verified)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Terverifikasi
                                    </span>
                                @endif
                            </div>
                            <div class="text-end">
                                <div class="mb-2 d-flex align-items-center">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <span class="fw-bold">{{ number_format($creativeEconomy->average_rating, 1) }}</span>
                                    <span class="text-muted ms-1">({{ $creativeEconomy->reviews->count() }} ulasan)</span>
                                </div>
                                <small class="text-muted">
                                    Bergabung sejak {{ $creativeEconomy->establishment_year }}
                                </small>
                            </div>
                        </div>

                        <!-- Description -->
                        <p>{{ $creativeEconomy->description }}</p>

                        <!-- Business Info -->
                        <div class="mt-2 row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-light rounded-circle">
                                            <i class="bi bi-geo-alt text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted d-block">Lokasi</small>
                                        <span>{{ $creativeEconomy->address }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-light rounded-circle">
                                            <i class="bi bi-telephone text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted d-block">Kontak</small>
                                        <span>{{ $creativeEconomy->phone_number }}</span>
                                    </div>
                                </div>
                            </div>
                            @if ($creativeEconomy->business_hours)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="p-2 bg-light rounded-circle">
                                                <i class="bi bi-clock text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <small class="text-muted d-block">Jam Operasional</small>
                                            <span>{{ $creativeEconomy->business_hours }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($creativeEconomy->employees_count)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="p-2 bg-light rounded-circle">
                                                <i class="bi bi-people text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <small class="text-muted d-block">Jumlah Karyawan</small>
                                            <span>{{ $creativeEconomy->employees_count }} orang</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Workshop Info -->
                        @if ($creativeEconomy->has_workshop)
                            <div class="mt-4 alert alert-info">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-info-circle-fill fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="alert-heading">Workshop Tersedia</h6>
                                        <p class="mb-0">{{ $creativeEconomy->workshop_information }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="card">
            <div class="bg-white card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Produk yang Tersedia</h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        Urutkan
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Terbaru</a></li>
                        <li><a class="dropdown-item" href="#">Harga Terendah</a></li>
                        <li><a class="dropdown-item" href="#">Harga Tertinggi</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @forelse($creativeEconomy->products as $product)
                        <div class="col-6 col-md-3">
                            <div class="border-0 shadow-sm card h-100">
                                @if ($product->featured_image)
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $product->featured_image) }}" class="card-img-top"
                                            alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                        @if ($product->is_featured)
                                            <div class="top-0 m-2 position-absolute start-0">
                                                <span class="badge bg-danger">Unggulan</span>
                                            </div>
                                        @endif
                                        @if ($product->discounted_price > 0)
                                            <div class="top-0 m-2 position-absolute end-0">
                                                <span class="badge bg-warning">
                                                    -{{ $product->discount_percentage }}%
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="mb-1 card-title">{{ $product->name }}</h6>
                                    <p class="mb-2 text-muted small">SKU: {{ $product->sku }}</p>
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                        @if ($product->discounted_price > 0)
                                            <div>
                                                <span class="text-decoration-line-through text-muted small">
                                                    {{ $product->formatted_price }}
                                                </span>
                                                <span class="fw-bold text-primary">
                                                    {{ $product->formatted_discounted_price }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="fw-bold text-primary">
                                                {{ $product->formatted_price }}
                                            </span>
                                        @endif

                                        @if ($product->in_stock)
                                            <span class="badge bg-success">Stok: {{ $product->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">Stok Habis</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-white border-0 card-footer">
                                    <div class="gap-2 d-grid">
                                        @if ($product->in_stock)
                                            <a href="https://wa.me/{{ $creativeEconomy->phone_number }}?text=Halo, saya tertarik dengan produk {{ $product->name }} (SKU: {{ $product->sku }})"
                                                class="btn btn-success btn-sm" target="_blank">
                                                <i class="bi bi-whatsapp me-2"></i>
                                                Pesan Sekarang
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#productModal"
                                            onclick="showProductDetails({{ $product->id }})">
                                            Detail Produk
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="py-5 text-center">
                                <img src="{{ asset('images/empty-product.svg') }}" alt="Tidak ada produk" class="mb-3"
                                    style="max-width: 200px">
                                <h5>Belum ada produk</h5>
                                <p class="text-muted">Produk akan segera ditambahkan</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="border-0 modal-header">
                    <h5 class="text-white modal-title" id="galleryCaption"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="p-0 modal-body">
                    <img src="" id="galleryImage" class="img-fluid w-100" alt="Gallery preview">
                </div>
                <div class="border-0 modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" id="prevImage">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-outline-light" id="nextImage">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProductName"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .gallery-item {
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .gallery-item:hover {
                transform: scale(1.02);
            }

            .gallery-item::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.1);
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .gallery-item:hover::after {
                opacity: 1;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function showProductDetails(productId) {
                const products = @json($creativeEconomy->products);
                const product = products.find(p => p.id === productId);

                if (!product) return;

                document.getElementById('modalProductName').textContent = product.name;

                const formattedPrice = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(product.price);

                const formattedDiscountedPrice = product.discounted_price ? new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(product.discounted_price) : null;

                const discountPercentage = product.price > 0 && product.discounted_price > 0 ?
                    Math.round(((product.price - product.discounted_price) / product.price) * 100) :
                    0;

                const priceHtml = product.discounted_price > 0 ?
                    `<div>
            <span class="text-decoration-line-through text-muted">${formattedPrice}</span>
            <span class="fw-bold text-primary fs-5">${formattedDiscountedPrice}</span>
            ${discountPercentage > 0 ? `<span class="badge bg-warning ms-2">-${discountPercentage}%</span>` : ''}
           </div>` :
                    `<span class="fw-bold text-primary fs-5">${formattedPrice}</span>`;

                const modalContent = `
        <div class="row">
            <div class="col-md-6">
                <img src="${product.featured_image ? '/storage/' + product.featured_image : '/images/no-image.jpg'}"
                     class="rounded img-fluid"
                     alt="${product.name}">
            </div>
            <div class="col-md-6">
                <h6>Spesifikasi Produk</h6>
                <ul class="list-unstyled">
                    ${product.material ? `
                                <li class="mb-2">
                                    <small class="text-muted">Material:</small><br>
                                    ${product.material}
                                </li>
                            ` : ''}
                    ${product.size ? `
                                <li class="mb-2">
                                    <small class="text-muted">Ukuran:</small><br>
                                    ${product.size}
                                </li>
                            ` : ''}
                    ${product.weight ? `
                                <li class="mb-2">
                                    <small class="text-muted">Berat:</small><br>
                                    ${product.weight}
                                </li>
                            ` : ''}
                </ul>
                <hr>
                <div class="mb-3">
                    <h6>Harga</h6>
                    ${priceHtml}
                </div>
                <p>${product.description || ''}</p>
            </div>
        </div>
    `;

                document.getElementById('modalContent').innerHTML = modalContent;
            }



            document.addEventListener('DOMContentLoaded', function() {
                const galleryModal = document.getElementById('galleryModal');
                const galleryImage = document.getElementById('galleryImage');
                const galleryCaption = document.getElementById('galleryCaption');
                const prevButton = document.getElementById('prevImage');
                const nextButton = document.getElementById('nextImage');

                // Get all gallery items
                const galleryItems = document.querySelectorAll('.gallery-item');
                let currentIndex = 0;

                // Add click event to gallery items
                galleryItems.forEach((item, index) => {
                    item.addEventListener('click', function() {
                        currentIndex = index;
                        const imageUrl = this.getAttribute('data-image');
                        const caption = this.getAttribute('data-caption');

                        // Show modal
                        const modal = new bootstrap.Modal(galleryModal);
                        modal.show();

                        updateGalleryModal(imageUrl, caption);
                        updateNavigationButtons();
                    });
                });

                // Navigation buttons
                prevButton.addEventListener('click', () => navigateGallery('prev'));
                nextButton.addEventListener('click', () => navigateGallery('next'));

                // Keyboard navigation
                document.addEventListener('keydown', function(event) {
                    if (!galleryModal.classList.contains('show')) return;

                    if (event.key === 'ArrowLeft') {
                        navigateGallery('prev');
                    } else if (event.key === 'ArrowRight') {
                        navigateGallery('next');
                    }
                });

                function navigateGallery(direction) {
                    if (direction === 'prev') {
                        currentIndex = currentIndex > 0 ? currentIndex - 1 : galleryItems.length - 1;
                    } else {
                        currentIndex = currentIndex < galleryItems.length - 1 ? currentIndex + 1 : 0;
                    }

                    const item = galleryItems[currentIndex];
                    updateGalleryModal(
                        item.getAttribute('data-image'),
                        item.getAttribute('data-caption')
                    );
                    updateNavigationButtons();
                }

                function updateGalleryModal(imageUrl, caption) {
                    galleryImage.src = imageUrl;
                    galleryCaption.textContent = caption || '';
                }

                function updateNavigationButtons() {
                    prevButton.style.display = galleryItems.length > 1 ? 'block' : 'none';
                    nextButton.style.display = galleryItems.length > 1 ? 'block' : 'none';
                }
            });
        </script>
    @endpush
@endsection
