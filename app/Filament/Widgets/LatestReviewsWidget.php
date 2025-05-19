<?php

namespace App\Filament\Widgets;

use App\Models\Review;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestReviewsWidget extends BaseWidget
{
    protected static ?string $heading = 'Ulasan Terbaru';

    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Review::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reviewable_type')
                    ->label('Tipe')
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'App\Models\Destination' => 'Destinasi',
                            'App\Models\Accommodation' => 'Akomodasi',
                            'App\Models\TravelPackage' => 'Paket Wisata',
                            default => $state,
                        };
                    }),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->url(fn (Review $record): string => route('filament.admin.resources.reviews.view', $record))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
