<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\StatisticsResource;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\TravelPackage;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class BookingStatistics extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StatisticsResource::class;
    protected static string $view = 'filament.resources.statistics-resource.pages.booking-statistics';

    public ?array $data = [];
    public $bookingStats = [];
    public $bookingDistribution = [];
    public $topBookedDestinations = [];
    public $recentBookings = [];
    public $bookingTrends = [];
    public $bookingsByPackage = [];
    public $bookingsPerformance = [];
    public $paymentStats = [];

    // Summary metrics
    public $totalBookings = 0;
    public $totalRevenue = 0;
    public $averageBookingValue = 0;
    public $conversionRate = 0;
    public $topPerformingDays = [];
    public $cancelledBookings = 0;
    public $completedBookings = 0;

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->subDays(30)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'booking_type' => 'all', // Default to all booking types
            'payment_status' => 'all', // Default to all payment statuses
        ]);

        // Load initial statistics
        $this->updateStats();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Card::make()
                    ->schema([
                        Components\Grid::make(4)
                            ->schema([
                                Components\DatePicker::make('start_date')
                                    ->label('Dari Tanggal')
                                    ->required()
                                    ->default(now()->subDays(30))
                                    ->maxDate(now()),

                                Components\DatePicker::make('end_date')
                                    ->label('Sampai Tanggal')
                                    ->required()
                                    ->default(now())
                                    ->minDate(function (callable $get) {
                                        $startDate = $get('start_date');
                                        return $startDate ? Carbon::parse($startDate) : null;
                                    })
                                    ->maxDate(now()),

                                Components\Select::make('booking_type')
                                    ->label('Tipe Pemesanan')
                                    ->options([
                                        'all' => 'Semua Tipe',
                                        'destination' => 'Destinasi',
                                        'package' => 'Paket Wisata',
                                        'event' => 'Event',
                                        'accommodation' => 'Akomodasi',
                                    ])
                                    ->default('all'),

                                Components\Select::make('payment_status')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        'all' => 'Semua Status',
                                        'pending' => 'Menunggu Pembayaran',
                                        'paid' => 'Sudah Dibayar',
                                        'cancelled' => 'Dibatalkan',
                                        'refunded' => 'Dikembalikan',
                                    ])
                                    ->default('all'),
                            ]),
                    ])
            ])
            ->statePath('data');
    }

    public function updateStats(): void
    {
        $startDate = Carbon::parse($this->data['start_date']);
        $endDate = Carbon::parse($this->data['end_date']);
        $bookingType = $this->data['booking_type'];
        $paymentStatus = $this->data['payment_status'];

        // Base query
        $query = Booking::whereBetween('created_at', [$startDate, $endDate]);

        // Filter by booking type if selected
        if ($bookingType !== 'all') {
            $query->where('booking_type', $bookingType);
        }

        // Filter by payment status if selected
        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }

        // Get basic stats
        $this->totalBookings = $query->count();
        $this->totalRevenue = $query->sum('total_amount');
        $this->averageBookingValue = $this->totalBookings > 0 ? $this->totalRevenue / $this->totalBookings : 0;

        // Get booking completion and cancellation stats
        $this->completedBookings = (clone $query)->where('status', 'completed')->count();
        $this->cancelledBookings = (clone $query)->where('status', 'cancelled')->count();
        $this->conversionRate = $this->totalBookings > 0 ? ($this->completedBookings / $this->totalBookings) * 100 : 0;

        // Get booking distribution by type
        $this->bookingDistribution = DB::table('bookings')
            ->select('booking_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($paymentStatus !== 'all', function ($query) use ($paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })
            ->groupBy('booking_type')
            ->get();

        // Get daily booking stats
        $this->bookingStats = DB::table('bookings')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('AVG(total_amount) as average_value')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($bookingType !== 'all', function ($query) use ($bookingType) {
                return $query->where('booking_type', $bookingType);
            })
            ->when($paymentStatus !== 'all', function ($query) use ($paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('d M Y');
                return $item;
            });

        // Get top booked destinations
        if ($bookingType === 'all' || $bookingType === 'destination') {
            $this->topBookedDestinations = Destination::withCount(['bookings' => function ($query) use ($startDate, $endDate, $paymentStatus) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                    if ($paymentStatus !== 'all') {
                        $query->where('payment_status', $paymentStatus);
                    }
                }])
                ->withSum(['bookings' => function ($query) use ($startDate, $endDate, $paymentStatus) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                    if ($paymentStatus !== 'all') {
                        $query->where('payment_status', $paymentStatus);
                    }
                }], 'total_amount')
                ->having('bookings_count', '>', 0)
                ->orderByDesc('bookings_count')
                ->limit(10)
                ->get();
        } else {
            $this->topBookedDestinations = collect([]);
        }

        // Get bookings by package
        if ($bookingType === 'all' || $bookingType === 'package') {
            $this->bookingsByPackage = TravelPackage::withCount(['bookings' => function ($query) use ($startDate, $endDate, $paymentStatus) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                    if ($paymentStatus !== 'all') {
                        $query->where('payment_status', $paymentStatus);
                    }
                }])
                ->withSum(['bookings' => function ($query) use ($startDate, $endDate, $paymentStatus) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                    if ($paymentStatus !== 'all') {
                        $query->where('payment_status', $paymentStatus);
                    }
                }], 'total_amount')
                ->having('bookings_count', '>', 0)
                ->orderByDesc('bookings_count')
                ->limit(10)
                ->get();
        } else {
            $this->bookingsByPackage = collect([]);
        }

        // Get recent bookings
        $this->recentBookings = Booking::with(['user', 'bookable'])
            ->when($bookingType !== 'all', function ($query) use ($bookingType) {
                return $query->where('booking_type', $bookingType);
            })
            ->when($paymentStatus !== 'all', function ($query) use ($paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        // Get booking trends over time
        $this->bookingTrends = DB::table('bookings')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('ROUND(AVG(total_amount), 2) as average_value')
            )
            ->when($bookingType !== 'all', function ($query) use ($bookingType) {
                return $query->where('booking_type', $bookingType);
            })
            ->when($paymentStatus !== 'all', function ($query) use ($paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $item->formatted_month = Carbon::createFromFormat('Y-m', $item->month)->format('M Y');
                return $item;
            });

        // Get payment stats
        $this->paymentStats = DB::table('bookings')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($bookingType !== 'all', function ($query) use ($bookingType) {
                return $query->where('booking_type', $bookingType);
            })
            ->when($paymentStatus !== 'all', function ($query) use ($paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();

        // Get performance by day of week
        $this->topPerformingDays = DB::table('bookings')
            ->select(
                DB::raw('DAYNAME(created_at) as day_name'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($bookingType !== 'all', function ($query) use ($bookingType) {
                return $query->where('booking_type', $bookingType);
            })
            ->when($paymentStatus !== 'all', function ($query) use ($paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })
            ->groupBy('day_name')
            ->orderByRaw('
                CASE day_name
                    WHEN "Sunday" THEN 1
                    WHEN "Monday" THEN 2
                    WHEN "Tuesday" THEN 3
                    WHEN "Wednesday" THEN 4
                    WHEN "Thursday" THEN 5
                    WHEN "Friday" THEN 6
                    WHEN "Saturday" THEN 7
                END
            ')
            ->get()
            ->map(function ($item) {
                $dayNames = [
                    'Sunday' => 'Minggu',
                    'Monday' => 'Senin',
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu',
                ];

                $item->day_name_id = $dayNames[$item->day_name] ?? $item->day_name;
                return $item;
            });

        // Calculate conversion rate and performance indicators
        $this->bookingsPerformance = [
            'completion_rate' => $this->totalBookings > 0 ? ($this->completedBookings / $this->totalBookings) * 100 : 0,
            'cancellation_rate' => $this->totalBookings > 0 ? ($this->cancelledBookings / $this->totalBookings) * 100 : 0,
        ];
    }
}
