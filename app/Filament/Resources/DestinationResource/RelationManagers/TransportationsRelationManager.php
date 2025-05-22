<?php

namespace App\Filament\Resources\DestinationResource\RelationManagers;

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

                Tables\Columns\TextColumn::make('pivot.service_type')
                    ->label('Jenis Layanan')
                    ->badge(),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga Dasar')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('pivot.notes')
                    ->label('Catatan')
                    ->limit(30),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Pilih Transportasi')
                            ->required(),
                        Forms\Components\Select::make('service_type')
                            ->label('Jenis Layanan')
                            ->options([
                                'regular' => 'Regular',
                                'premium' => 'Premium',
                                'charter' => 'Charter',
                            ])
                            ->required(),
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
                                'premium' => 'Premium',
                                'charter' => 'Charter',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan'),
                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
