<?php

namespace App\Filament\Widgets;

use App\Models\Destination;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DestinationStatsWidget extends ChartWidget
{
    protected static ?string $heading = 'Destinasi Berdasarkan Kategori';

    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $data = Destination::select('categories.name', DB::raw('count(*) as total'))
            ->join('categories', 'destinations.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Destinasi',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#F59E0B',
                        '#10B981',
                        '#3B82F6',
                        '#8B5CF6',
                        '#EC4899',
                        '#EF4444',
                        '#F97316',
                        '#06B6D4',
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
