<?php

namespace App\Filament\Resources\CulturalHeritageResource\Pages;

use App\Filament\Resources\CulturalHeritageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCulturalHeritage extends EditRecord
{
    protected static string $resource = CulturalHeritageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
