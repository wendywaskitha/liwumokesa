{{-- resources/views/filament/resources/accommodation/view.blade.php --}}
<div class="space-y-4">
    <div class="aspect-w-16 aspect-h-9">
        <img src="{{ Storage::url($record->featured_image) }}"
             class="object-cover w-full h-full rounded-lg"
             alt="{{ $record->name }}">
    </div>

    <div class="space-y-2">
        <h3 class="text-lg font-bold">{{ $record->name }}</h3>
        <p class="text-sm text-gray-500">{{ $record->type }}</p>

        <div class="flex items-center space-x-2">
            <x-heroicon-o-map-pin class="w-4 h-4 text-primary-500"/>
            <span class="text-sm">{{ number_format($distance, 1) }} km dari destinasi</span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium">Kisaran Harga</p>
                <p class="text-sm">Rp {{ number_format($record->price_range_start) }} - {{ number_format($record->price_range_end) }}</p>
            </div>
            <div>
                <p class="text-sm font-medium">Kontak</p>
                <p class="text-sm">{{ $record->phone_number }}</p>
            </div>
        </div>

        @if($record->facilities)
            <div>
                <p class="mb-2 text-sm font-medium">Fasilitas</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($record->facilities as $facility)
                        <span class="px-2 py-1 text-xs bg-gray-100 rounded-full">
                            {{ $facility }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <p class="text-sm">{{ $record->description }}</p>
    </div>
</div>
