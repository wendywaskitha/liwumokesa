<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900">Cuaca di Muna Barat</h3>
        <div class="mt-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <!-- Weather icon -->
                    <img src="{{ $weatherIcon }}" alt="Weather" class="h-16 w-16">
                    <div class="ml-4">
                        <p class="text-3xl font-bold text-gray-900">{{ $temperature }}°C</p>
                        <p class="text-gray-500">{{ $condition }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Kelembaban: {{ $humidity }}%</p>
                    <p class="text-sm text-gray-500">Angin: {{ $windSpeed }} km/h</p>
                </div>
            </div>
            <!-- 3-day forecast -->
            <div class="mt-6 grid grid-cols-3 gap-4">
                @foreach($forecast as $day)
                    <div class="text-center">
                        <p class="text-sm text-gray-500">{{ $day['date'] }}</p>
                        <img src="{{ $day['icon'] }}" alt="Weather" class="h-8 w-8 mx-auto my-2">
                        <p class="text-sm font-medium text-gray-900">{{ $day['temp'] }}°C</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
