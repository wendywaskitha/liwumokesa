<?php

namespace App\Filament\Resources\DestinationResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CreativeEconomyRelationManager extends RelationManager
{
    protected static string $relationship = 'creativeEconomies';
    protected static ?string $title = 'Ekonomi Kreatif';
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
                    ->label('Nama UMKM')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),

                Tables\Columns\TextColumn::make('price_range_start')
                    ->label('Harga Mulai')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('price_range_end')
                    ->label('Harga Sampai')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('owner_name')
                    ->label('Pemilik')
                    ->searchable(),

                Tables\Columns\IconColumn::make('has_workshop')
                    ->label('Workshop')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Terverifikasi')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Kategori'),

                Tables\Filters\SelectFilter::make('district_id')
                    ->relationship('district', 'name')
                    ->label('Kecamatan'),

                Tables\Filters\TernaryFilter::make('has_workshop')
                    ->label('Workshop'),

                Tables\Filters\TernaryFilter::make('has_direct_selling')
                    ->label('Penjualan Langsung'),

                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Terverifikasi'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Pilih UMKM')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('partnership_type')
                            ->label('Jenis Kerjasama')
                            ->options([
                                'reseller' => 'Reseller',
                                'workshop' => 'Workshop',
                                'display' => 'Display Only',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tampilkan di Highlight'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3),
                        Forms\Components\TextInput::make('workshop_schedule')
                            ->label('Jadwal Workshop')
                            ->visible(fn ($get) => $get('partnership_type') === 'workshop'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('partnership_type')
                            ->label('Jenis Kerjasama')
                            ->options([
                                'reseller' => 'Reseller',
                                'workshop' => 'Workshop',
                                'display' => 'Display Only',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tampilkan di Highlight'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3),
                        Forms\Components\TextInput::make('workshop_schedule')
                            ->label('Jadwal Workshop')
                            ->visible(fn ($get) => $get('partnership_type') === 'workshop'),
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
