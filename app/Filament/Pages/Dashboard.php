<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DestinationStatsWidget;
use App\Filament\Widgets\LatestReviewsWidget;
use App\Filament\Widgets\TravelStatsOverview;
use App\Filament\Widgets\UpcomingEventsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getColumns(): int | array
    {
        return 3;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TravelStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getWidgets(): array
    {
        return [
            DestinationStatsWidget::class,
            LatestReviewsWidget::class,
            UpcomingEventsWidget::class,
        ];
    }
}
