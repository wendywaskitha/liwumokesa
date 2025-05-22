<?php

namespace App\Filament\Resources\DestinationResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CulinaryRelationManager extends RelationManager
{
    protected static string $relationship = 'culinaries';
    protected static ?string $title = 'Kuliner';
    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kuliner')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'restaurant' => 'success',
                        'cafe' => 'info',
                        'street_food' => 'warning',
                        'traditional' => 'danger',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('price_range')
                    ->label('Kisaran Harga')
                    ->getStateUsing(function ($record) {
                        return 'Rp ' . number_format($record->price_range_start, 0, ',', '.') . ' - ' . number_format($record->price_range_end, 0, ',', '.');
                    }),

                Tables\Columns\IconColumn::make('halal_certified')
                    ->label('Halal')
                    ->boolean(),

                Tables\Columns\IconColumn::make('has_vegetarian_option')
                    ->label('Vegetarian')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Kuliner')
                    ->options([
                        'restaurant' => 'Restoran',
                        'cafe' => 'Kafe',
                        'street_food' => 'Kaki Lima',
                        'traditional' => 'Warung Tradisional',
                    ]),

                Tables\Filters\SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name'),

                Tables\Filters\TernaryFilter::make('halal_certified')
                    ->label('Sertifikasi Halal'),

                Tables\Filters\TernaryFilter::make('has_vegetarian_option')
                    ->label('Opsi Vegetarian'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Pilih Kuliner')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('service_type')
                            ->label('Jenis Layanan')
                            ->options([
                                'regular' => 'Regular',
                                'special' => 'Spesial',
                                'recommended' => 'Rekomendasi',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_recommended')
                            ->label('Rekomendasi')
                            ->default(false),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('service_type')
                            ->label('Jenis Layanan')
                            ->options([
                                'regular' => 'Regular',
                                'special' => 'Spesial',
                                'recommended' => 'Rekomendasi',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_recommended')
                            ->label('Rekomendasi'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan'),
                    ]),
                Tables\Actions\DetachAction::make()
                    ->label('Hapus dari Destinasi'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->label('Hapus dari Destinasi'),
            ]);
    }
}
