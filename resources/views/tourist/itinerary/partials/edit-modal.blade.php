{{-- resources/views/tourist/itinerary/partials/edit-modal.blade.php --}}
@foreach($itineraries as $itinerary)
<div class="modal fade" id="editItineraryModal{{ $itinerary->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Rencana Perjalanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tourist.itinerary.update', $itinerary) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Rencana</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name', $itinerary->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               name="start_date"
                               value="{{ old('start_date', $itinerary->start_date->format('Y-m-d')) }}"
                               required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Paket Wisata</label>
                        <select class="form-select @error('travel_package_ids') is-invalid @enderror"
                                name="travel_package_ids[]"
                                multiple
                                required>
                            @foreach($travelPackages as $package)
                                <option value="{{ $package->id }}"
                                        {{ in_array($package->id, $itinerary->travelPackages->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('travel_package_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control"
                                  name="notes"
                                  rows="3">{{ old('notes', $itinerary->notes) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
