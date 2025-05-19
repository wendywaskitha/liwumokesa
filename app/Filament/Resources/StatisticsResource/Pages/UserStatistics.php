<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\StatisticsResource;
use App\Models\User;
use App\Models\Visit;
use App\Models\Review;
use App\Models\Booking;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Carbon;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components;
use Illuminate\Support\Facades\DB;

class UserStatistics extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StatisticsResource::class;
    protected static string $view = 'filament.resources.statistics-resource.pages.user-statistics';

    public ?array $data = [];
    public $registrationTrend = [];
    public $usersByRole = [];
    public $usersByRegion = [];
    public $userActivity = [];
    public $mostActiveUsers = [];
    public $userRetention = [];
    public $summaryStats = [];

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->subDays(90)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'group_by' => 'day',
            'user_role' => 'all',
        ]);

        $this->updateStats();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Grid::make(4)
                    ->schema([
                        Components\DatePicker::make('start_date')
                            ->label('Dari Tanggal')
                            ->required()
                            ->default(now()->subDays(90)),

                        Components\DatePicker::make('end_date')
                            ->label('Sampai Tanggal')
                            ->required()
                            ->default(now()),

                        Components\Select::make('group_by')
                            ->label('Kelompokkan Berdasarkan')
                            ->options([
                                'day' => 'Harian',
                                'week' => 'Mingguan',
                                'month' => 'Bulanan',
                            ])
                            ->default('day'),

                        Components\Select::make('user_role')
                            ->label('Peran Pengguna')
                            ->options([
                                'all' => 'Semua Peran',
                                'admin' => 'Admin',
                                'tourist' => 'Wisatawan',
                            ])
                            ->default('all'),
                    ])
            ])
            ->statePath('data');
    }

    public function updateStats(): void
    {
        $startDate = Carbon::parse($this->data['start_date']);
        $endDate = Carbon::parse($this->data['end_date']);
        $groupBy = $this->data['group_by'];
        $userRole = $this->data['user_role'];

        // Build base query
        $query = User::whereBetween('created_at', [$startDate, $endDate]);

        // Apply role filter if not "all"
        if ($userRole !== 'all') {
            $query->where('role', $userRole);
        }

        // Get registration trend based on group_by
        $dateFormat = $groupBy === 'day' ? '%Y-%m-%d' : ($groupBy === 'week' ? '%Y-%u' : '%Y-%m');
        $dateFormatDisplay = $groupBy === 'day' ? 'd M' : ($groupBy === 'week' ? 'W-y' : 'M Y');

        $this->registrationTrend = $query->clone()
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as date_group"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date_group')
            ->orderBy('date_group')
            ->get()
            ->map(function ($item) use ($dateFormatDisplay, $groupBy) {
                // Format the date for display based on group_by
                if ($groupBy === 'day') {
                    $formattedDate = Carbon::parse($item->date_group)->format($dateFormatDisplay);
                } elseif ($groupBy === 'week') {
                    list($year, $week) = explode('-', $item->date_group);
                    $formattedDate = "W{$week}, {$year}";
                } else {
                    list($year, $month) = explode('-', $item->date_group);
                    $formattedDate = Carbon::createFromDate($year, $month, 1)->format('M Y');
                }

                return [
                    'date_group' => $item->date_group,
                    'formatted_date' => $formattedDate,
                    'count' => $item->count,
                ];
            });

        // Get users by role
        $this->usersByRole = User::select(
                'role',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('role')
            ->get();

        // Get users by region (if applicable in your app)
        $this->usersByRegion = User::whereNotNull('region')
            ->select(
                'region',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('region')
            ->orderBy('count', 'desc')
            ->get();

        // Get user activity metrics
        $this->userActivity = [
            'visits' => Visit::whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'reviews' => Review::whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'average_visits_per_user' => Visit::whereBetween('created_at', [$startDate, $endDate])
                ->select('user_id', DB::raw('COUNT(*) as visit_count'))
                ->groupBy('user_id')
                ->avg('visit_count') ?? 0,
        ];

        // Get most active users
        $this->mostActiveUsers = User::select(
                'users.id',
                'users.name',
                'users.email',
                'users.role'
            )
            ->addSelect(DB::raw('
                (SELECT COUNT(*) FROM visits WHERE visits.user_id = users.id AND visits.created_at BETWEEN ? AND ?) as visit_count
            '))
            ->addSelect(DB::raw('
                (SELECT COUNT(*) FROM reviews WHERE reviews.user_id = users.id AND reviews.created_at BETWEEN ? AND ?) as review_count
            '))
            ->addSelect(DB::raw('
                (SELECT COUNT(*) FROM bookings WHERE bookings.user_id = users.id AND bookings.created_at BETWEEN ? AND ?) as booking_count
            '))
            ->addSelect(DB::raw('
                (visit_count + review_count + booking_count) as total_activity
            '))
            ->setBindings([$startDate, $endDate, $startDate, $endDate, $startDate, $endDate])
            ->orderBy('total_activity', 'desc')
            ->take(10)
            ->get();

        // Get user retention data (n-day retention)
        $retentionPeriods = [7, 30, 60, 90];
        $this->userRetention = [];

        foreach ($retentionPeriods as $days) {
            // Get users who registered in the period
            $registeredUsers = User::whereBetween('created_at', [
                    $startDate,
                    $endDate->copy()->subDays($days)
                ])->count();

            if ($registeredUsers > 0) {
                // Get users who remained active after N days
                $activeUsers = User::whereBetween('created_at', [
                        $startDate,
                        $endDate->copy()->subDays($days)
                    ])
                    ->whereExists(function ($query) use ($days) {
                        $query->select(DB::raw(1))
                            ->from('visits')
                            ->whereRaw('visits.user_id = users.id')
                            ->whereRaw("visits.created_at > DATE_ADD(users.created_at, INTERVAL {$days} DAY)");
                    })
                    ->count();

                $this->userRetention[] = [
                    'days' => $days,
                    'retention_rate' => round(($activeUsers / $registeredUsers) * 100, 1),
                    'active_users' => $activeUsers,
                    'total_users' => $registeredUsers,
                ];
            } else {
                $this->userRetention[] = [
                    'days' => $days,
                    'retention_rate' => 0,
                    'active_users' => 0,
                    'total_users' => 0,
                ];
            }
        }

        // Get summary statistics
        $this->summaryStats = [
            'total_users' => User::count(),
            'new_users' => $query->clone()->count(),
            'active_users' => Visit::whereBetween('created_at', [$startDate, $endDate])
                ->distinct('user_id')
                ->count(),
            'tourist_percentage' => User::count() > 0
                ? round(User::where('role', 'tourist')->count() * 100 / User::count(), 1)
                : 0,
            'admin_percentage' => User::count() > 0
                ? round(User::where('role', 'admin')->count() * 100 / User::count(), 1)
                : 0,
            'average_daily_active_users' => round(Visit::whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(DISTINCT user_id) as active_users'))
                ->groupBy('date')
                ->avg('active_users') ?? 0, 0),
        ];
    }
}
