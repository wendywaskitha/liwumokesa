<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Item Rencana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tourist.itinerary.items.update', ['itinerary' => $itinerary->id, 'item' => $item->id]) }}"
                  method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Day Input -->
                    <div class="mb-3">
                        <label class="form-label">Hari Ke <span class="text-danger">*</span></label>
                        <select class="form-select @error('day') is-invalid @enderror"
                                name="day"
                                required>
                            @for($i = 1; $i <= $itinerary->start_date->diffInDays($itinerary->end_date) + 1; $i++)
                                <option value="{{ $i }}" {{ $item->day == $i ? 'selected' : '' }}>
                                    Hari {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('day')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Time Range -->
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                            <input type="time"
                                   class="form-control @error('start_time') is-invalid @enderror"
                                   name="start_time"
                                   value="{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}"
                                   required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                            <input type="time"
                                   class="form-control @error('end_time') is-invalid @enderror"
                                   name="end_time"
                                   value="{{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}"
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                name="notes"
                                rows="3"
                                placeholder="Tambahkan catatan untuk item ini...">{{ $item->notes }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estimated Cost -->
                    <div class="mb-3">
                        <label class="form-label">Estimasi Biaya</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number"
                                   class="form-control @error('estimated_cost') is-invalid @enderror"
                                   name="estimated_cost"
                                   value="{{ $item->estimated_cost }}"
                                   placeholder="0">
                        </div>
                        @error('estimated_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
