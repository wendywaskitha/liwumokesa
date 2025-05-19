<div class="space-y-4 p-2">
    <div class="flex items-center space-x-4">
        @if($tourGuide->photo)
            <img src="{{ \Storage::url($tourGuide->photo) }}"
                class="w-20 h-20 rounded-full object-cover"
                alt="{{ $tourGuide->name }}">
        @else
            <div class="w-20 h-20 rounded-full bg-primary-500 flex items-center justify-center text-white text-xl font-bold">
                {{ substr($tourGuide->name, 0, 1) }}
            </div>
        @endif

        <div>
            <h3 class="text-lg font-bold">{{ $tourGuide->name }}</h3>
            <div class="text-sm text-gray-500">
                <span class="inline-flex items-center">
                    <x-heroicon-s-star class="w-4 h-4 text-yellow-400 mr-1" />
                    {{ number_format($tourGuide->rating ?? 0, 1) }}/5.0
                </span>
                <span class="ml-2">{{ $tourGuide->experience_years }} tahun pengalaman</span>
            </div>
        </div>
    </div>

    @if($tourGuide->description)
        <div>
            <h4 class="font-semibold text-sm uppercase text-gray-500">Biografi</h4>
            <p class="text-sm mt-1">{{ $tourGuide->description }}</p>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4">
        <div>
            <h4 class="font-semibold text-sm uppercase text-gray-500">Bahasa</h4>
            <div class="flex flex-wrap gap-1 mt-1">
                @if(is_array($tourGuide->languages))
                    @foreach($tourGuide->languages as $language)
                        <span class="px-2 py-1 rounded-full bg-primary-100 text-primary-700 text-xs">
                            {{ $language }}
                        </span>
                    @endforeach
                @elseif(is_string($tourGuide->languages))
                    @foreach(explode(',', $tourGuide->languages) as $language)
                        <span class="px-2 py-1 rounded-full bg-primary-100 text-primary-700 text-xs">
                            {{ trim($language) }}
                        </span>
                    @endforeach
                @endif
            </div>
        </div>

        <div>
            <h4 class="font-semibold text-sm uppercase text-gray-500">Kontak</h4>
            <div class="space-y-1 mt-1">
                @if($tourGuide->phone)
                    <div class="flex items-center text-sm">
                        <x-heroicon-s-phone class="w-4 h-4 mr-2" />
                        {{ $tourGuide->phone }}
                    </div>
                @endif

                @if($tourGuide->email)
                    <div class="flex items-center text-sm">
                        <x-heroicon-s-mail class="w-4 h-4 mr-2" />
                        {{ $tourGuide->email }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div>
        <h4 class="font-semibold text-sm uppercase text-gray-500">Status</h4>
        <div class="mt-1">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $tourGuide->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                @if($tourGuide->is_available)
                    <x-heroicon-s-check-circle class="w-4 h-4 mr-1" />
                    Tersedia untuk Booking
                @else
                    <x-heroicon-s-x-circle class="w-4 h-4 mr-1" />
                    Tidak Tersedia
                @endif
            </span>
        </div>
    </div>
</div>
