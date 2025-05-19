<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\StatisticsResource;
use App\Models\Destination;
use App\Models\Review;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class ReviewStatistics extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StatisticsResource::class;
    protected static string $view = 'filament.resources.statistics-resource.pages.review-statistics';

    public ?array $data = [];
    public $reviewStats = [];
    public $reviewDistribution = [];
    public $topRatedDestinations = [];
    public $recentReviews = [];
    public $reviewTrends = [];
    public $selectedDestinationId = null;
    public $destinations = [];
    public $reviewCount = 0;
    public $averageRating = 0;
    public $reviewsByRating = [];

    public function mount(): void
    {
        // Get all destinations for the filter
        $this->destinations = Destination::pluck('name', 'id')->toArray();

        // Set default values for the form
        $this->form->fill([
            'start_date' => now()->subDays(30)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'destination_id' => 'all', // Default to all destinations
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
                        Components\Grid::make(3)
                            ->schema([
                                Components\Select::make('destination_id')
                                    ->label('Destinasi')
                                    ->options(function() {
                                        return ['all' => 'Semua Destinasi'] + $this->destinations;
                                    })
                                    ->default('all')
                                    ->reactive(),

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
                            ]),
                    ])
            ])
            ->statePath('data');
    }

    public function updateStats(): void
    {
        $startDate = Carbon::parse($this->data['start_date']);
        $endDate = Carbon::parse($this->data['end_date']);
        $selectedDestination = $this->data['destination_id'];

        // Store the selected destination ID
        $this->selectedDestinationId = $selectedDestination;

        // Base query
        $query = Review::whereBetween('created_at', [$startDate, $endDate]);

        // Filter by destination if selected
        if ($selectedDestination !== 'all') {
            $query->where('reviewable_type', Destination::class)
                  ->where('reviewable_id', $selectedDestination);
        }

        // Get basic stats
        $this->reviewCount = $query->count();
        $this->averageRating = $query->avg('rating') ?? 0;

        // Get review distribution by rating (1-5 stars)
        $this->reviewDistribution = collect(range(1, 5))
            ->map(function ($rating) use ($query, $selectedDestination, $startDate, $endDate) {
                $ratingQuery = clone $query;
                $count = $ratingQuery->where('rating', $rating)->count();
                $percentage = $this->reviewCount > 0 ? ($count / $this->reviewCount) * 100 : 0;

                return [
                    'rating' => $rating,
                    'count' => $count,
                    'percentage' => round($percentage, 1),
                ];
            })->toArray();

        // Review stats by day
        $this->reviewStats = Review::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('ROUND(AVG(rating), 1) as average_rating')
            )
            ->when($selectedDestination !== 'all', function ($query) use ($selectedDestination) {
                return $query->where('reviewable_type', Destination::class)
                           ->where('reviewable_id', $selectedDestination);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('d M Y');
                return $item;
            });

        // Get top rated destinations (with at least 3 reviews)
        $this->topRatedDestinations = Destination::withCount(['reviews' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withAvg(['reviews' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }], 'rating')
            ->having('reviews_count', '>=', 3) // At least 3 reviews to be considered
            ->orderByDesc('reviews_avg_rating')
            ->limit(10)
            ->get();

        // Get recent reviews
        $this->recentReviews = Review::with(['reviewable', 'user'])
            ->when($selectedDestination !== 'all', function ($query) use ($selectedDestination) {
                return $query->where('reviewable_type', Destination::class)
                           ->where('reviewable_id', $selectedDestination);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        // Get review trends over time (monthly)
        $this->reviewTrends = Review::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('ROUND(AVG(rating), 1) as average_rating')
            )
            ->when($selectedDestination !== 'all', function ($query) use ($selectedDestination) {
                return $query->where('reviewable_type', Destination::class)
                           ->where('reviewable_id', $selectedDestination);
            })
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $item->formatted_month = Carbon::createFromFormat('Y-m', $item->month)->format('M Y');
                return $item;
            });

        // Reviews by rating
        $this->reviewsByRating = $this->reviewDistribution;
    }
}
