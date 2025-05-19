<?php

namespace App\Filament\Resources\CulturalHeritageResource\Pages;

use App\Filament\Resources\CulturalHeritageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCulturalHeritages extends ListRecords
{
    protected static string $resource = CulturalHeritageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
