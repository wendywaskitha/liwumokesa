<?php

namespace App\Filament\Resources\CulinaryResource\Pages;

use App\Filament\Resources\CulinaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCulinaries extends ListRecords
{
    protected static string $resource = CulinaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
