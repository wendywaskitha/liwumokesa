<?php

namespace App\Filament\Resources\CulinaryResource\Pages;

use App\Filament\Resources\CulinaryResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCulinary extends ViewRecord
{
    protected static string $resource = CulinaryResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure featured_menu is decoded for viewing
        if (isset($data['featured_menu']) && is_string($data['featured_menu'])) {
            $decodedMenu = json_decode($data['featured_menu'], true);
            if (is_array($decodedMenu)) {
                $data['featured_menu'] = $decodedMenu;
            }
        }

        return $data;
    }
}
