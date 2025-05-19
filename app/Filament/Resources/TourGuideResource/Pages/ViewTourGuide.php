<?php

namespace App\Filament\Resources\TourGuideResource\Pages;

use App\Filament\Resources\TourGuideResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTourGuide extends ViewRecord
{
    protected static string $resource = TourGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
