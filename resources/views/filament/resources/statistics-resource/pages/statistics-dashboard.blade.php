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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Summary cards for quick overview -->
        <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-primary-50 rounded-lg">
                    <x-heroicon-o-users class="w-8 h-8 text-primary-500" />
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Total Pengunjung</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($summaryStats['total_visitors'] ?? 0) }}</div>
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
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($summaryStats['average_rating'] ?? 0, 1) }}</div>
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
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($summaryStats['total_reviews'] ?? 0) }}</div>
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
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($summaryStats['total_bookings'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-danger-50 rounded-lg">
                    <x-heroicon-o-currency-dollar class="w-8 h-8 text-danger-500" />
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Total Pendapatan</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">Rp {{ number_format($summaryStats['total_revenue'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-secondary-50 rounded-lg">
                    <x-heroicon-o-user-plus class="w-8 h-8 text-secondary-500" />
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Pengguna Baru</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($summaryStats['new_users'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Visitor trend chart -->
        <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Pengunjung</h3>
            <div id="visitor-chart" class="w-full h-64"></div>
        </div>

        <!-- Review trend chart -->
        <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Ulasan & Rating</h3>
            <div id="review-chart" class="w-full h-64"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Booking trend chart -->
        <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Pemesanan</h3>
            <div id="booking-chart" class="w-full h-64"></div>
        </div>

        <!-- Top destinations chart -->
        <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Destinasi Terpopuler</h3>
            <div id="destination-chart" class="w-full h-64"></div>
        </div>
    </div>

    <!-- Top destinations table -->
    <div class="mt-6 bg-white rounded-xl shadow p-4 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Perbandingan Destinasi</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinasi</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pengunjung</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ulasan</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesanan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($destinationStats as $destination)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $destination->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-900">{{ number_format($destination->visits_count ?? 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-900">{{ number_format($destination->reviews_avg_rating ?? 0, 1) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-900">{{ number_format($destination->reviews_count ?? 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-900">{{ number_format($destination->bookings_count ?? 0) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data destinasi yang tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            // Visitor Chart
            const visitorData = @json($visitorStats ?? []);
            if (visitorData.length > 0) {
                const visitorChart = new ApexCharts(document.querySelector("#visitor-chart"), {
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
                document.getElementById('visitor-chart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Data tidak tersedia</div>';
            }

            // Review Chart
            const reviewData = @json($reviewStats ?? []);
            if (reviewData.length > 0) {
                const reviewChart = new ApexCharts(document.querySelector("#review-chart"), {
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
                document.getElementById('review-chart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Data tidak tersedia</div>';
            }

            // Booking Chart
            const bookingData = @json($bookingStats ?? []);
            if (bookingData.length > 0) {
                const bookingChart = new ApexCharts(document.querySelector("#booking-chart"), {
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
                document.getElementById('booking-chart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Data tidak tersedia</div>';
            }

            // Destination Chart
            const destinationData = @json($destinationStats ? $destinationStats->take(5) : []);
            if (destinationData.length > 0) {
                const destinationChart = new ApexCharts(document.querySelector("#destination-chart"), {
                    chart: {
                        type: 'bar',
                        height: 250,
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Pengunjung',
                        data: destinationData.map(item => item.visits_count || 0)
                    }],
                    xaxis: {
                        categories: destinationData.map(item => item.name)
                    },
                    colors: ['#8B5CF6'],
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 4,
                            distributed: true
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        textAnchor: 'start',
                        formatter: function (val, opt) {
                            return val.toLocaleString()
                        },
                        style: {
                            colors: ['#fff']
                        },
                        offsetX: 0
                    }
                });
                destinationChart.render();
            } else {
                document.getElementById('destination-chart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Data tidak tersedia</div>';
            }
        });
    </script>
    @endpush
</x-filament-panels::page>
