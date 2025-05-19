<?php

namespace App\Livewire\Tourist;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherWidget extends Component
{
    public $weather;
    public $forecast;
    public $loading = true;
    public $error = null;

    public function mount()
    {
        $this->loadWeather();
    }

    public function loadWeather()
    {
        try {
            // Try to get from cache first
            $weatherData = Cache::remember('muna_barat_weather', 1800, function () {
                $response = Http::get('https://api.weatherapi.com/v1/forecast.php', [
                    'key' => config('services.weather.key'),
                    'q' => 'Muna Barat',
                    'days' => 3,
                    'aqi' => 'no'
                ]);

                if ($response->failed()) {
                    throw new \Exception('Failed to fetch weather data');
                }

                return $response->json();
            });

            $this->weather = [
                'temp_c' => $weatherData['current']['temp_c'],
                'condition' => $weatherData['current']['condition']['text'],
                'icon' => $weatherData['current']['condition']['icon'],
                'humidity' => $weatherData['current']['humidity'],
                'wind_kph' => $weatherData['current']['wind_kph'],
            ];

            $this->forecast = collect($weatherData['forecast']['forecastday'])
                ->map(function ($day) {
                    return [
                        'date' => \Carbon\Carbon::parse($day['date'])->format('D, d M'),
                        'temp_c' => $day['day']['avgtemp_c'],
                        'condition' => $day['day']['condition']['text'],
                        'icon' => $day['day']['condition']['icon'],
                    ];
                });

        } catch (\Exception $e) {
            $this->error = 'Gagal memuat data cuaca';
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.tourist.weather-widget');
    }
}
