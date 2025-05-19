<x-filament-panels::page>
    <form wire:submit="updateStats" class="mb-6">
        {{ $this->form }}

        <x-filament::button
            type="submit"
            class="mt-4"
        >
            Update Statistik
        </x-filament::button>
    </form>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-primary-50 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Ulasan</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($reviewCount) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-warning-50 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Rating Rata-rata</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($averageRating, 1) }}</p>
                    <div class="flex items-center mt-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($averageRating))
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Filter Aktif</h3>
            <div class="text-gray-900">
                <p><span class="font-medium">Destinasi:</span>
                    {{ $selectedDestinationId === 'all' ? 'Semua Destinasi' : ($destinations[$selectedDestinationId] ?? 'Tidak ditemukan') }}
                </p>
                <p><span class="font-medium">Periode:</span>
                    {{ \Carbon\Carbon::parse($data['start_date'])->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($data['end_date'])->format('d M Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Rating Distribution Chart -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Distribusi Rating</h3>
            <div id="rating-distribution-chart" class="h-72"></div>
        </div>

        <!-- Review Trend Chart -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Ulasan & Rating</h3>
            <div id="review-trend-chart" class="h-72"></div>
        </div>
    </div>

    <!-- Top Rated Destinations -->
    <div class="bg-white rounded-xl shadow p-6 border border-gray-200 mb-6">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Destinasi dengan Rating Tertinggi</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinasi</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Ulasan</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rating Rata-rata</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bintang</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topRatedDestinations as $destination)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $destination->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $destination->reviews_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center font-medium">
                            {{ number_format($destination->reviews_avg_rating, 1) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= round($destination->reviews_avg_rating))
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            Tidak ada data destinasi dengan minimal 3 ulasan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Ulasan Terbaru</h3>
        <div class="space-y-6">
            @forelse($recentReviews as $review)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor

                            <span class="ml-2 text-sm text-gray-500">{{ $review->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            {{ $review->reviewable->name ?? 'Destinasi tidak ditemukan' }}
                        </p>
                        <p class="mt-2 text-sm text-gray-700">{{ $review->content }}</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        <p>{{ $review->user->name ?? 'Pengguna anonim' }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center p-4 text-gray-500">
                Tidak ada ulasan terbaru
            </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            // Rating Distribution Chart
            const ratingData = @json($reviewsByRating);
            const distributionChart = new ApexCharts(document.querySelector("#rating-distribution-chart"), {
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Jumlah Ulasan',
                    data: ratingData.map(item => item.count)
                }],
                xaxis: {
                    categories: ratingData.map(item => item.rating + ' Bintang'),
                    labels: {
                        rotate: 0
                    }
                },
                colors: ['#F59E0B'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%',
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },
                grid: {
                    padding: {
                        top: 30
                    }
                },
                annotations: {
                    yaxis: [
                        {
                            y: 0,
                            borderColor: '#ddd',
                            strokeDashArray: 0,
                            borderWidth: 1,
                        }
                    ]
                }
            });
            distributionChart.render();

            // Review Trend Chart
            const reviewStats = @json($reviewStats);
            const trendChart = new ApexCharts(document.querySelector("#review-trend-chart"), {
                chart: {
                    type: 'line',
                    height: 280,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                series: [{
                    name: 'Jumlah Ulasan',
                    type: 'column',
                    data: reviewStats.map(item => item.count)
                }, {
                    name: 'Rating Rata-rata',
                    type: 'line',
                    data: reviewStats.map(item => item.average_rating)
                }],
                stroke: {
                    width: [0, 4]
                },
                xaxis: {
                    categories: reviewStats.map(item => item.formatted_date),
                    labels: {
                        rotate: -45,
                        rotateAlways: true,
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: [
                    {
                        title: {
                            text: 'Jumlah Ulasan'
                        },
                        min: 0
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Rating'
                        },
                        min: 0,
                        max: 5,
                        tickAmount: 5,
                        forceNiceScale: true
                    }
                ],
                colors: ['#60A5FA', '#F59E0B'],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'top'
                }
            });
            trendChart.render();
        });

        document.addEventListener('livewire:update', () => {
            if (window.trendChart) {
                window.trendChart.destroy();
            }
            if (window.distributionChart) {
                window.distributionChart.destroy();
            }

            // Re-initialize charts will be handled by Alpine.js when data changes
        });
    </script>
    @endpush

</x-filament-panels::page>
