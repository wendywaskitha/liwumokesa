<?php

namespace App\Filament\Resources\TourGuideResource\Pages;

use App\Filament\Resources\TourGuideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTourGuide extends EditRecord
{
    protected static string $resource = TourGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
