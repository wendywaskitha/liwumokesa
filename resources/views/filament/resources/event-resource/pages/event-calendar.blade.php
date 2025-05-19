<x-filament::page>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold">{{ $this->getCalendarData()['month'] }} {{ $this->getCalendarData()['year'] }}</h2>
        <div class="flex items-center space-x-4">
            <x-filament::button color="secondary" wire:click="previousMonth">
                <x-heroicon-o-chevron-left class="w-5 h-5" />
                Bulan Sebelumnya
            </x-filament::button>
            
            <x-filament::button color="secondary" wire:click="nextMonth">
                Bulan Berikutnya
                <x-heroicon-o-chevron-right class="w-5 h-5" />
            </x-filament::button>
        </div>
    </div>
    
    @if($culturalHeritageId)
        <div class="mb-4 p-3 border rounded-md bg-primary-50 text-primary-700">
            <div class="flex items-center">
                <x-heroicon-o-information-circle class="w-5 h-5 mr-2" />
                <p>Menampilkan acara yang terkait dengan warisan budaya ID: {{ $culturalHeritageId }}</p>
            </div>
            <div class="mt-2">
                <x-filament::button 
                    color="primary" 
                    size="sm"
                    tag="a" 
                    :href="route('filament.admin.resources.events.calendar')"
                >
                    Lihat Semua Acara
                </x-filament::button>
            </div>
        </div>
    @endif
    
    <div class="border rounded-lg overflow-hidden">
        <div class="grid grid-cols-7 bg-gray-100 font-medium">
            <div class="px-4 py-2 text-center border-r">Senin</div>
            <div class="px-4 py-2 text-center border-r">Selasa</div>
            <div class="px-4 py-2 text-center border-r">Rabu</div>
            <div class="px-4 py-2 text-center border-r">Kamis</div>
            <div class="px-4 py-2 text-center border-r">Jumat</div>
            <div class="px-4 py-2 text-center border-r">Sabtu</div>
            <div class="px-4 py-2 text-center">Minggu</div>
        </div>
        
        @foreach($this->getCalendarData()['weeks'] as $week)
            <div class="grid grid-cols-7 border-t">
                @foreach($week as $day)
                    @if($day)
                        <div class="p-2 border-r h-32 overflow-y-auto {{ $day['isToday'] ? 'bg-primary-50' : '' }}">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium {{ $day['isToday'] ? 'text-primary-600' : '' }}">{{ $day['day'] }}</span>
                                @if($day['events']->count() > 0)
                                    <span class="px-1.5 py-0.5 text-xs font-medium bg-primary-100 text-primary-800 rounded-full">
                                        {{ $day['events']->count() }}
                                    </span>
                                @endif
                            </div>
                            
                            @foreach($day['events'] as $event)
                                <div class="mb-1 p-1 text-xs rounded bg-primary-100 hover:bg-primary-200 transition-colors">
                                    <a href="{{ route('filament.admin.resources.events.edit', $event) }}" class="block">
                                        <div class="font-medium truncate">{{ $event->name }}</div>
                                        <div class="text-gray-500 truncate">{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-2 border-r h-32 bg-gray-50"></div>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
    
    <div class="mt-6">
        <h3 class="text-lg font-medium mb-4">Daftar Acara Bulan Ini</h3>
        <div class="overflow-hidden border rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Acara</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($events as $event)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $event->name }}</td>
                                <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $event->location }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('filament.admin.resources.events.edit', $event) }}" class="text-primary-600 hover:text-primary-900">
                                            Edit
                                        </a>
                                        <a href="{{ route('filament.admin.resources.events.view', $event) }}" class="text-gray-600 hover:text-gray-900">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada acara untuk bulan ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament::page>
