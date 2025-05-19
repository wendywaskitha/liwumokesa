<?php

namespace App\Filament\Widgets;

use App\Models\Destination;
use App\Models\Review;
use App\Models\TravelPackage;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TravelStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Destinasi', Destination::where('status', true)->count())
                ->description('Destinasi wisata aktif')
                ->descriptionIcon('heroicon-m-map')
                ->color('success'),

            Stat::make('Total Paket Wisata', TravelPackage::where('status', true)->count())
                ->description('Paket wisata tersedia')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Total Wisatawan', User::where('role', 'wisatawan')->count())
                ->description('Pengguna terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),

            Stat::make('Total Ulasan', Review::count())
                ->description('Ulasan pengguna')
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->color('danger'),
        ];
    }
}
