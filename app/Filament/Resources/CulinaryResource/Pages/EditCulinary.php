<?php

namespace App\Filament\Resources\CulinaryResource\Pages;

use App\Filament\Resources\CulinaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCulinary extends EditRecord
{
    protected static string $resource = CulinaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
