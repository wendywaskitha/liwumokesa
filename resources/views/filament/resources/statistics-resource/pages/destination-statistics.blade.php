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

    @if($selectedDestinationId && isset($destinationStats['destination']))
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $destinationStats['destination']->name }}</h2>
                    <p class="text-gray-500 mt-1">{{ $destinationStats['destination']->category->name ?? 'Tidak berkategori' }}</p>
                </div>

                <div class="mt-4 md:mt-0">
                    <a href="{{ route('filament.admin.resources.destinations.edit', $destinationStats['destination']->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <x-heroicon-o-pencil-square class="w-4 h-4 mr-1.5" />
                        Edit Destinasi
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-primary-50 rounded-lg">
                        <x-heroicon-o-users class="w-8 h-8 text-primary-500" />
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Total Pengunjung</div>
                        <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($destinationStats['summary']['total_visitors'] ?? 0) }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-success-50 rounded-lg">
                        <x-heroicon-o-star class="w-8 h-8 text-success-500" />
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Rating Rata-rata</div>
                        <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($destinationStats['summary']['average_rating'] ?? 0, 1) }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-warning-50 rounded-lg">
                        <x-heroicon-o-chat-bubble-left class="w-8 h-8 text-warning-500" />
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Total Ulasan</div>
                        <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($destinationStats['summary']['total_reviews'] ?? 0) }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-info-50 rounded-lg">
                        <x-heroicon-o-ticket class="w-8 h-8 text-info-500" />
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Total Pemesanan</div>
                        <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($destinationStats['summary']['total_bookings'] ?? 0) }}</div>
                        <div class="text-sm text-gray-500">Rp {{ number_format($destinationStats['summary']['total_revenue'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Visitor trend chart -->
            <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Pengunjung</h3>
                <div id="destination-visitor-chart" class="w-full h-64"></div>
            </div>

            <!-- Review trend chart -->
            <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Ulasan & Rating</h3>
                <div id="destination-review-chart" class="w-full h-64"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Booking trend chart -->
            <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Pemesanan</h3>
                <div id="destination-booking-chart" class="w-full h-64"></div>
            </div>

            <!-- Revenue chart -->
            <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Pendapatan</h3>
                <div id="destination-revenue-chart" class="w-full h-64"></div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200 text-center">
            <div class="flex flex-col items-center justify-center py-12">
                <x-heroicon-o-map class="w-16 h-16 text-gray-400" />
                <h3 class="mt-4 text-lg font-medium text-gray-900">Pilih Destinasi</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Silakan pilih destinasi dan rentang tanggal di atas untuk melihat statistik
                </p>
            </div>
        </div>
    @endif

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            @if($selectedDestinationId && isset($destinationStats['visit_stats']))
                // Visitor Chart
                const visitorData = @json($destinationStats['visit_stats'] ?? []);
                if (visitorData.length > 0) {
                    const visitorChart = new ApexCharts(document.querySelector("#destination-visitor-chart"), {
                        chart: {
                            type: 'area',
                            height: 250,
                            toolbar: {
                                show: false
                            }
                        },
                        series: [{
                            name: 'Pengunjung',
                            data: visitorData.map(item => item.visitors)
                        }],
                        xaxis: {
                            categories: visitorData.map(item => item.formatted_date)
                        },
                        colors: ['#6366F1'],
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'light',
                                type: 'vertical',
                                shadeIntensity: 0.5,
                                opacityFrom: 0.7,
                                opacityTo: 0.2,
                            }
                        }
                    });
                    visitorChart.render();
                } else {
                    document.getElementById('destination-visitor-chart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Data tidak tersedia</div>';
                }

                // Review Chart
                const reviewData = @json($destinationStats['review_stats'] ?? []);
                if (reviewData.length > 0) {
                    const reviewChart = new ApexCharts(document.querySelector("#destination-review-chart"), {
                        chart: {
                            type: 'line',
                            height: 250,
                            toolbar: {
                                show: false
                            }
                        },
                        series: [{
                            name: 'Rating',
                            data: reviewData.map(item => item.avg_rating),
                            type: 'line'
                        }, {
                            name: 'Ulasan',
                            data: reviewData.map(item => item.count),
                            type: 'bar'
                        }],
                        xaxis: {
                            categories: reviewData.map(item => item.formatted_date)
                        },
                        yaxis: [
                            {
                                title: {
                                    text: 'Rating'
                                },
                                min: 0,
                                max: 5
                            },
                            {
                                opposite: true,
                                title: {
                                    text: 'Jumlah Ulasan'
                                }
                            }
                        ],
                        colors: ['#F59E0B', '#10B981'],
                        stroke: {
                            curve: 'smooth',
                            width: [3, 0]
                        }
                    });
                    reviewChart.render();
                } else {
                    document.getElementById('destination-review-chart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Data tidak tersedia</div>';
                }

                // Booking Chart
                const bookingData = @json($destinationStats['booking_stats'] ?? []);
                if (bookingData.length > 0) {
                    const bookingChart = new ApexCharts(document.querySelector("#destination-booking-chart"), {
                        chart: {
                            type: 'bar',
                            height: 250,
                            toolbar: {
                                show: false
                            }
                        },
                        series: [{
                            name: 'Pemesanan',
                            data: bookingData.map(item => item.count)
                        }],
                        xaxis: {
                            categories: bookingData.map(item => item.formatted_date)
                        },
                        colors: ['#0EA5E9'],
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                columnWidth: '60%',
                            }
                        }
                    });
                    bookingChart.render();
                } else {
                    document.getElementById('destination-booking-chart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Data tidak tersedia</div>';
                }

                // Revenue Chart
                const revenueData = @json($destinationStats['booking_stats'] ?? []);
                if (revenueData.length > 0) {
                    const revenueChart = new ApexCharts(document.querySelector("#destination-revenue-chart"), {
                        chart: {
                            type: 'area',
                            height: 250,
                            toolbar: {
                                show: false
                            }
                        },
                        series: [{
                            name: 'Pendapatan',
                            data: revenueData.map(item => item.revenue || 0)
                        }],
                        xaxis: {
                            categories: revenueData.map(item => item.formatted_date)
                        },
                        colors: ['#EF4444'],
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'light',
                                type: 'vertical',
                                shadeIntensity: 0.5,
                                opacityFrom: 0.7,
                                opacityTo: 0.2,
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return 'Rp ' + val.toLocaleString()
                                }
                            }
                        },
                    });
                    revenueChart.render();
                } else {
                    document.getElementById('destination-revenue-chart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Data tidak tersedia</div>';
                }
            @endif
        });
    </script>
    @endpush
</x-filament-panels::page>
