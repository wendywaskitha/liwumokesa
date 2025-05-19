<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\StatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStatistics extends ViewRecord
{
    protected static string $resource = StatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
