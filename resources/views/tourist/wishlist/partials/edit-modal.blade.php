<div class="modal fade" id="editWishlistModal{{ $wishlist->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Wishlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tourist.wishlist.notes', $wishlist) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <!-- Priority Input -->
                    <div class="mb-3">
                        <label class="form-label">Prioritas</label>
                        @php
                            $priorities = [
                                1 => 'Prioritas Rendah',
                                2 => 'Prioritas Cukup Rendah',
                                3 => 'Prioritas Sedang',
                                4 => 'Prioritas Cukup Tinggi',
                                5 => 'Prioritas Tinggi'
                            ];
                        @endphp

                        <select name="priority" class="form-select" required>
                            @foreach($priorities as $value => $label)
                                <option value="{{ $value }}" {{ $wishlist->priority == $value ? 'selected' : '' }}>
                                    {{ $value }} - {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Notes Input -->
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control"
                                name="notes"
                                rows="3"
                                placeholder="Tambahkan catatan untuk wishlist ini...">{{ old('notes', $wishlist->notes) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
