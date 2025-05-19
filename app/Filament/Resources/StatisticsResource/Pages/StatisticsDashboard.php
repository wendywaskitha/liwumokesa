<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\StatisticsResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Carbon;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components;

class StatisticsDashboard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StatisticsResource::class;

    protected static string $view = 'filament.resources.statistics-resource.pages.statistics-dashboard';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->subDays(30)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);

        $this->updateStats();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Grid::make(2)
                    ->schema([
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

        $this->summaryStats = StatisticsResource::getSummaryStats($startDate, $endDate);
        $this->visitorStats = StatisticsResource::getVisitorStats($startDate, $endDate);
        $this->destinationStats = StatisticsResource::getDestinationStats($startDate, $endDate);
        $this->reviewStats = StatisticsResource::getReviewStats($startDate, $endDate);
        $this->bookingStats = StatisticsResource::getBookingStats($startDate, $endDate);
        $this->userRegistrationStats = StatisticsResource::getUserRegistrationStats($startDate, $endDate);
    }
}
