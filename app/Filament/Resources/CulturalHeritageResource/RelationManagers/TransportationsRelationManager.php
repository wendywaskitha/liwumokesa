<?php

namespace App\Filament\Resources\CulturalHeritageResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TransportationsRelationManager extends RelationManager
{
    protected static string $relationship = 'transportations';
    protected static ?string $title = 'Transportasi';
    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Transportasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'darat' => 'success',
                        'laut' => 'info',
                        'udara' => 'warning',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('subtype')
                    ->label('Sub Jenis'),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->numeric(),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga Dasar')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Transportasi')
                    ->options([
                        'darat' => 'Transportasi Darat',
                        'laut' => 'Transportasi Laut',
                        'udara' => 'Transportasi Udara',
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
                            ->label('Pilih Transportasi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('service_type')
                            ->label('Jenis Layanan')
                            ->options([
                                'regular' => 'Regular',
                                'charter' => 'Charter',
                                'shuttle' => 'Shuttle',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('route_notes')
                            ->label('Catatan Rute'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Tambahan'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('service_type')
                            ->label('Jenis Layanan')
                            ->options([
                                'regular' => 'Regular',
                                'charter' => 'Charter',
                                'shuttle' => 'Shuttle',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('route_notes')
                            ->label('Catatan Rute'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Tambahan'),
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
