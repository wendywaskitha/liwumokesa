<?php

namespace App\Filament\Resources\TourGuideResource\Pages;

use App\Filament\Resources\TourGuideResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTourGuides extends ListRecords
{
    protected static string $resource = TourGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
