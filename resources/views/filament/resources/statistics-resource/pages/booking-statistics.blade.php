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

    <!-- Key Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-primary-50 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Pemesanan</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalBookings) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-success-50 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-success-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Pendapatan</h3>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-warning-50 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Nilai Rata-rata</h3>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($averageBookingValue) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-info-50 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-info-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Tingkat Konversi</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($conversionRate, 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Booking Trend Chart -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Pemesanan</h3>
            <div id="booking-trend-chart" class="h-72"></div>
        </div>

        <!-- Revenue Trend Chart -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Pendapatan</h3>
            <div id="revenue-trend-chart" class="h-72"></div>
        </div>

        <!-- Booking Type Distribution Chart -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Distribusi Tipe Pemesanan</h3>
            <div id="booking-type-chart" class="h-72"></div>
        </div>

        <!-- Payment Method Chart -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Metode Pembayaran</h3>
            <div id="payment-method-chart" class="h-72"></div>
        </div>
    </div>

    <!-- Performance by Day of Week -->
    <div class="bg-white rounded-xl shadow p-6 border border-gray-200 mb-6">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Kinerja Berdasarkan Hari</h3>
        <div id="day-performance-chart" class="h-72"></div>
    </div>

    <!-- Destinations Section -->
    @if($topBookedDestinations->isNotEmpty())
    <div class="bg-white rounded-xl shadow p-6 border border-gray-200 mb-6">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Destinasi Terpopuler</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinasi</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pemesanan</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($topBookedDestinations as $destination)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $destination->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $destination->bookings_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center font-medium">
                            Rp {{ number_format($destination->bookings_sum_total_amount) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            Rp {{ number_format($destination->bookings_count > 0 ? $destination->bookings_sum_total_amount / $destination->bookings_count : 0) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Packages Section -->
    @if($bookingsByPackage->isNotEmpty())
    <div class="bg-white rounded-xl shadow p-6 border border-gray-200 mb-6">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Paket Wisata Terpopuler</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket Wisata</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pemesanan</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookingsByPackage as $package)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $package->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $package->bookings_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center font-medium">
                            Rp {{ number_format($package->bookings_sum_total_amount) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            Rp {{ number_format($package->bookings_count > 0 ? $package->bookings_sum_total_amount / $package->bookings_count : 0) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Pemesanan Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->booking_code ?? '#' . $booking->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $booking->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'Tamu' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $booking->bookable->name ?? 'Item tidak ditemukan' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($booking->booking_type == 'destination') bg-blue-100 text-blue-800
                                @elseif($booking->booking_type == 'package') bg-green-100 text-green-800
                                @elseif($booking->booking_type == 'event') bg-purple-100 text-purple-800
                                @elseif($booking->booking_type == 'accommodation') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @switch($booking->booking_type)
                                    @case('destination') Destinasi @break
                                    @case('package') Paket Wisata @break
                                    @case('event') Event @break
                                    @case('accommodation') Akomodasi @break
                                    @default {{ ucfirst($booking->booking_type) }} @break
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->total_amount) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($booking->status == 'completed') bg-green-100 text-green-800
                                @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @switch($booking->status)
                                    @case('completed') Selesai @break
                                    @case('pending') Menunggu @break
                                    @case('cancelled') Dibatalkan @break
                                    @default {{ ucfirst($booking->status) }} @break
                                @endswitch
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            Tidak ada data pemesanan
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
            // Booking Trend Chart
            const bookingStats = @json($bookingStats);
            const bookingTrendChart = new ApexCharts(document.querySelector("#booking-trend-chart"), {
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
                    name: 'Jumlah Pemesanan',
                    data: bookingStats.map(item => item.count)
                }],
                stroke: {
                    curve: 'smooth',
                    width: 4
                },
                xaxis: {
                    categories: bookingStats.map(item => item.formatted_date),
                    labels: {
                        rotate: -45,
                        rotateAlways: true,
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                colors: ['#3B82F6'],
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
                grid: {
                    borderColor: '#f1f1f1'
                },
                markers: {
                    size: 4
                }
            });
            bookingTrendChart.render();

            // Revenue Trend Chart
            const revenueTrendChart = new ApexCharts(document.querySelector("#revenue-trend-chart"), {
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                series: [{
                    name: 'Pendapatan',
                    data: bookingStats.map(item => item.revenue)
                }],
                xaxis: {
                    categories: bookingStats.map(item => item.formatted_date),
                    labels: {
                        rotate: -45,
                        rotateAlways: true,
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                colors: ['#10B981'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '60%',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            });
            revenueTrendChart.render();

            // Booking Type Distribution
            const bookingDistribution = @json($bookingDistribution);
            const typeLabels = {
                'destination': 'Destinasi',
                'package': 'Paket Wisata',
                'event': 'Event',
                'accommodation': 'Akomodasi',
                'other': 'Lainnya'
            };

            const bookingTypeChart = new ApexCharts(document.querySelector("#booking-type-chart"), {
                chart: {
                    type: 'donut',
                    height: 280
                },
                series: bookingDistribution.map(item => item.count),
                labels: bookingDistribution.map(item => typeLabels[item.booking_type] || item.booking_type),
                colors: ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#6B7280'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '50%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                }
            });
            bookingTypeChart.render();

            // Payment Method Chart
            const paymentStats = @json($paymentStats);
            const paymentMethodChart = new ApexCharts(document.querySelector("#payment-method-chart"), {
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Jumlah Transaksi',
                    data: paymentStats.map(item => item.count)
                }],
                xaxis: {
                    categories: paymentStats.map(item => {
                        switch(item.payment_method) {
                            case 'bank_transfer': return 'Transfer Bank';
                            case 'credit_card': return 'Kartu Kredit';
                            case 'e_wallet': return 'E-Wallet';
                            case 'cash': return 'Tunai';
                            case 'other': return 'Lainnya';
                            default: return item.payment_method;
                        }
                    })
                },
                colors: ['#8B5CF6'],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 4,
                        columnWidth: '70%',
                        distributed: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                }
            });
            paymentMethodChart.render();

            // Day Performance Chart
            const dayPerformance = @json($topPerformingDays);
            const dayPerformanceChart = new ApexCharts(document.querySelector("#day-performance-chart"), {
                chart: {
                    type: 'bar',
                    height: 280,
                    stacked: false,
                    toolbar: {
                        show: false
                    }
                },
                series: [
                    {
                        name: 'Jumlah Pemesanan',
                        data: dayPerformance.map(item => item.count)
                    },
                    {
                        name: 'Pendapatan (Rp)',
                        data: dayPerformance.map(item => item.revenue / 10000) // Scale down for better visualization
                    }
                ],
                xaxis: {
                    categories: dayPerformance.map(item => item.day_name_id)
                },
                yaxis: [
                    {
                        title: {
                            text: 'Jumlah Pemesanan'
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Pendapatan (Rp10k)'
                        }
                    }
                ],
                colors: ['#0EA5E9', '#F97316'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: [0, 4]
                },
                fill: {
                    opacity: [0.85, 0.25],
                    gradient: {
                        inverseColors: false,
                        shade: 'light',
                        type: "vertical",
                        opacityFrom: 0.85,
                        opacityTo: 0.55,
                        stops: [0, 100, 100, 100]
                    }
                },
                markers: {
                    size: 0
                }
            });
            dayPerformanceChart.render();
        });
    </script>
    @endpush

</x-filament-panels::page>
