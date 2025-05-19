<?php

namespace App\Filament\Resources\CreativeEconomyResource\Pages;

use App\Filament\Resources\CreativeEconomyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreativeEconomy extends EditRecord
{
    protected static string $resource = CreativeEconomyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
