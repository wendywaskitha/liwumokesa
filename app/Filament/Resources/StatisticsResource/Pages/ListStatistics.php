<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\StatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatistics extends ListRecords
{
    protected static string $resource = StatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('dashboard')
                ->label('Dashboard')
                ->url(fn () => StatisticsResource::getUrl('dashboard'))
                ->icon('heroicon-o-presentation-chart-line')
                ->color('primary'),
            Actions\Action::make('destinations')
                ->label('Statistik Destinasi')
                ->url(fn () => StatisticsResource::getUrl('destinations'))
                ->icon('heroicon-o-map')
                ->color('secondary'),
            Actions\Action::make('users')
                ->label('Statistik Pengguna')
                ->url(fn () => StatisticsResource::getUrl('users'))
                ->icon('heroicon-o-user-group')
                ->color('secondary'),
            Actions\Action::make('reviews')
                ->label('Statistik Ulasan')
                ->url(fn () => StatisticsResource::getUrl('reviews'))
                ->icon('heroicon-o-chat-bubble-left')
                ->color('secondary'),
            Actions\Action::make('bookings')
                ->label('Statistik Pemesanan')
                ->url(fn () => StatisticsResource::getUrl('bookings'))
                ->icon('heroicon-o-ticket')
                ->color('secondary'),
        ];
    }
}
