<?php

namespace App\Filament\Resources\CreativeEconomyResource\Pages;

use App\Filament\Resources\CreativeEconomyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreativeEconomies extends ListRecords
{
    protected static string $resource = CreativeEconomyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
