<?php

namespace App\Filament\Resources\CulturalHeritageResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AmenitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'amenities';
    protected static ?string $title = 'Fasilitas';
    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            // ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            ->columns([
                // Tables\Columns\TextColumn::make('sort_order')
                //     ->label('Urutan')
                //     ->sortable(),

                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Fasilitas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge(),

                Tables\Columns\TextColumn::make('availability')
                    ->label('Ketersediaan')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        '24_hours' => '24 Jam',
                        'custom' => 'Jam Tertentu',
                        'closed' => 'Tutup',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        '24_hours' => 'success',
                        'custom' => 'warning',
                        'closed' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_free')
                    ->label('Gratis')
                    ->boolean(),

                Tables\Columns\TextColumn::make('fee')
                    ->label('Biaya')
                    ->money('IDR'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Fasilitas')
                    ->options([
                        'toilet' => 'Toilet',
                        'parking' => 'Parkir',
                        'worship' => 'Tempat Ibadah',
                        'rest_area' => 'Rest Area',
                        'atm' => 'ATM',
                    ]),

                Tables\Filters\SelectFilter::make('availability')
                    ->label('Ketersediaan')
                    ->options([
                        '24_hours' => '24 Jam',
                        'custom' => 'Jam Tertentu',
                        'closed' => 'Tutup',
                    ]),

                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('Fasilitas Gratis'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Pilih Fasilitas')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Toggle::make('is_accessible')
                            ->label('Dapat Diakses')
                            ->default(true),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Toggle::make('is_accessible')
                            ->label('Dapat Diakses'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan'),
                    ]),
                Tables\Actions\DetachAction::make()
                    ->label('Hapus dari Warisan Budaya'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->label('Hapus dari Warisan Budaya'),
            ]);
    }
}
