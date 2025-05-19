{{-- resources/views/livewire/tourist/weather-widget.blade.php --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900">Cuaca di Muna Barat</h3>

        @if($loading)
            <div class="flex justify-center items-center h-40">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            </div>
        @elseif($error)
            <div class="text-center text-red-600 py-4">
                {{ $error }}
            </div>
        @else
            <!-- Current Weather -->
            <div class="mt-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="{{ $weather['icon'] }}"
                             alt="Weather"
                             class="h-16 w-16">
                        <div class="ml-4">
                            <p class="text-3xl font-bold text-gray-900">
                                {{ round($weather['temp_c']) }}°C
                            </p>
                            <p class="text-gray-500">
                                {{ $weather['condition'] }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">
                            Kelembaban: {{ $weather['humidity'] }}%
                        </p>
                        <p class="text-sm text-gray-500">
                            Angin: {{ round($weather['wind_kph']) }} km/h
                        </p>
                    </div>
                </div>

                <!-- 3-day Forecast -->
                <div class="mt-6 grid grid-cols-3 gap-4 border-t pt-4">
                    @foreach($forecast as $day)
                        <div class="text-center">
                            <p class="text-sm text-gray-500">
                                {{ $day['date'] }}
                            </p>
                            <img src="{{ $day['icon'] }}"
                                 alt="Weather"
                                 class="h-8 w-8 mx-auto my-2">
                            <p class="text-sm font-medium text-gray-900">
                                {{ round($day['temp_c']) }}°C
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $day['condition'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
