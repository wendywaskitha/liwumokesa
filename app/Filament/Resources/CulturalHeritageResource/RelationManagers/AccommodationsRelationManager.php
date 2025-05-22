<?php

namespace App\Filament\Resources\CulturalHeritageResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AccommodationsRelationManager extends RelationManager
{
    protected static string $relationship = 'accommodations';
    protected static ?string $title = 'Akomodasi';
    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Akomodasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hotel' => 'primary',
                        'villa' => 'success',
                        'homestay' => 'warning',
                        'resort' => 'danger',
                        'guesthouse' => 'info',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('price_range_start')
                    ->label('Harga Mulai')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('price_range_end')
                    ->label('Harga Sampai')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Akomodasi')
                    ->options([
                        'hotel' => 'Hotel',
                        'villa' => 'Villa',
                        'homestay' => 'Homestay',
                        'resort' => 'Resort',
                        'guesthouse' => 'Guest House',
                    ]),

                Tables\Filters\SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Pilih Akomodasi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('partnership_type')
                            ->label('Jenis Kerjasama')
                            ->options([
                                'nearby' => 'Terdekat',
                                'recommended' => 'Rekomendasi',
                                'partner' => 'Partner',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_recommended')
                            ->label('Rekomendasi'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('partnership_type')
                            ->label('Jenis Kerjasama')
                            ->options([
                                'nearby' => 'Terdekat',
                                'recommended' => 'Rekomendasi',
                                'partner' => 'Partner',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_recommended')
                            ->label('Rekomendasi'),
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
