<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($getRecord()->galleries as $gallery)
        <div class="relative group">
            <img 
                src="{{ asset('storage/' . $gallery->file_path) }}" 
                alt="{{ $gallery->caption }}" 
                class="h-48 w-full object-cover rounded-lg transition duration-300 group-hover:opacity-90" 
                loading="lazy"
            />
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 opacity-0 group-hover:opacity-100 transition duration-300">
                <div class="text-white text-sm font-medium">{{ $gallery->caption }}</div>
                @if($gallery->category)
                    <div class="text-xs text-white/80 mt-1">
                        {{ match($gallery->category) {
                            'destination' => 'Destinasi',
                            'accommodation' => 'Akomodasi',
                            'transportation' => 'Transportasi',
                            'food' => 'Kuliner',
                            'activity' => 'Aktivitas',
                            'highlight' => 'Highlight',
                            default => $gallery->category,
                        } }}
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
