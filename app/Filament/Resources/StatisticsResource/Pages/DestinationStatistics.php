<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\StatisticsResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Carbon;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components;
use App\Models\Destination;

class DestinationStatistics extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StatisticsResource::class;

    protected static string $view = 'filament.resources.statistics-resource.pages.destination-statistics';

    public ?array $data = [];
    public $destinations = [];
    public $destinationStats = [];
    public $selectedDestinationId = null;

    public function mount(): void
    {
        $this->destinations = Destination::all();

        if ($this->destinations->isNotEmpty()) {
            $this->selectedDestinationId = $this->destinations->first()->id;
        }

        $this->form->fill([
            'start_date' => now()->subDays(30)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'destination_id' => $this->selectedDestinationId,
        ]);

        $this->updateStats();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Grid::make(3)
                    ->schema([
                        Components\Select::make('destination_id')
                            ->label('Destinasi')
                            ->options(Destination::pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedDestinationId = $state;
                                $this->updateStats();
                            }),
                        Components\DatePicker::make('start_date')
                            ->label('Dari Tanggal')
                            ->required()
                            ->default(now()->subDays(30)),
                        Components\DatePicker::make('end_date')
                            ->label('Sampai Tanggal')
                            ->required()
                            ->default(now()),
                    ])
            ])
            ->statePath('data');
    }

    public function updateStats(): void
    {
        $startDate = Carbon::parse($this->data['start_date']);
        $endDate = Carbon::parse($this->data['end_date']);

        // Get detailed stats for selected destination
        if ($this->selectedDestinationId) {
            $this->destinationStats = $this->getDestinationDetailedStats(
                $this->selectedDestinationId,
                $startDate,
                $endDate
            );
        }
    }

    private function getDestinationDetailedStats($destinationId, $startDate, $endDate)
    {
        $destination = Destination::findOrFail($destinationId);

        // Get daily visit statistics
        $visitStats = $destination->visits()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('COUNT(DISTINCT user_id) as visitors')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('d M');
                return $item;
            });

        // Get review statistics
        $reviewStats = $destination->reviews()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('ROUND(AVG(rating), 1) as avg_rating'),
                \DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('d M');
                return $item;
            });

        // Get booking statistics
        $bookingStats = $destination->bookings()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('COUNT(*) as count'),
                \DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->date)->format('d M');
                return $item;
            });

        // Summary statistics
        $summaryStats = [
            'total_visitors' => $destination->visits()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct('user_id')
                ->count('user_id'),

            'total_reviews' => $destination->reviews()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),

            'average_rating' => $destination->reviews()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('rating') ?? 0,

            'total_bookings' => $destination->bookings()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),

            'total_revenue' => $destination->bookings()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount'),
        ];

        return [
            'destination' => $destination,
            'visit_stats' => $visitStats,
            'review_stats' => $reviewStats,
            'booking_stats' => $bookingStats,
            'summary' => $summaryStats,
        ];
    }
}
