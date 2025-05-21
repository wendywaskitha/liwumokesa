<div class="modal fade" id="productDetail{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Product Image -->
                        <img src="{{ asset('storage/' . $product->featured_image) }}"
                             class="rounded img-fluid"
                             alt="{{ $product->name }}">

                        <!-- Product Gallery -->
                        @if($product->galleries->count() > 0)
                            <div class="mt-2 row g-2">
                                @foreach($product->galleries as $image)
                                    <div class="col-4">
                                        <img src="{{ asset('storage/' . $image->path) }}"
                                             class="rounded cursor-pointer img-fluid"
                                             onclick="showImage('{{ asset('storage/' . $image->path) }}')"
                                             alt="Gallery image">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <!-- Product Info -->
                        <div class="mb-3">
                            <h6>Spesifikasi Produk</h6>
                            <ul class="list-unstyled">
                                @if($product->material)
                                    <li class="mb-2">
                                        <small class="text-muted">Material:</small><br>
                                        {{ $product->material }}
                                    </li>
                                @endif
                                @if($product->size)
                                    <li class="mb-2">
                                        <small class="text-muted">Ukuran:</small><br>
                                        {{ $product->size }}
                                    </li>
                                @endif
                                @if($product->weight)
                                    <li class="mb-2">
                                        <small class="text-muted">Berat:</small><br>
                                        {{ $product->weight }}
                                    </li>
                                @endif
                                @if($product->colors)
                                    <li class="mb-2">
                                        <small class="text-muted">Warna Tersedia:</small><br>
                                        {{ $product->colors }}
                                    </li>
                                @endif
                                @if($product->dimensions)
                                    <li class="mb-2">
                                        <small class="text-muted">Dimensi:</small><br>
                                        {{ $product->dimensions }}
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Price Info -->
                        <div class="mb-3">
                            <h6>Harga</h6>
                            @if($product->discounted_price > 0)
                                <div>
                                    <span class="text-decoration-line-through text-muted">
                                        {{ $product->formatted_price }}
                                    </span>
                                    <span class="fw-bold text-primary fs-5">
                                        {{ $product->formatted_discounted_price }}
                                    </span>
                                    <span class="badge bg-warning ms-2">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                </div>
                            @else
                                <span class="fw-bold text-primary fs-5">
                                    {{ $product->formatted_price }}
                                </span>
                            @endif
                        </div>

                        <!-- Stock Info -->
                        <div class="mb-3">
                            <h6>Ketersediaan</h6>
                            @if($product->in_stock)
                                <span class="badge bg-success">Stok: {{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger">Stok Habis</span>
                            @endif

                            @if($product->is_custom_order)
                                <div class="mt-2 small text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    Waktu Produksi: {{ $product->production_time }} hari
                                </div>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <h6>Deskripsi Produk</h6>
                            <p>{{ $product->description }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="gap-2 d-grid">
                            @if($product->in_stock)
                                <a href="https://wa.me/{{ $creativeEconomy->phone_number }}?text=Halo, saya tertarik dengan produk {{ $product->name }} (SKU: {{ $product->sku }})"
                                   class="btn btn-success"
                                   target="_blank">
                                    <i class="bi bi-whatsapp me-2"></i>
                                    Pesan Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
