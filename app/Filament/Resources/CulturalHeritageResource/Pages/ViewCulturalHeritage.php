<?php

namespace App\Filament\Resources\CulturalHeritageResource\Pages;

use App\Filament\Resources\CulturalHeritageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCulturalHeritage extends ViewRecord
{
    protected static string $resource = CulturalHeritageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('share')
                ->label('Bagikan')
                ->icon('heroicon-o-share')
                ->color('success')
                ->url(fn ($record) => route('cultural-heritage.share', $record))
                ->openUrlInNewTab(),
        ];
    }
}
