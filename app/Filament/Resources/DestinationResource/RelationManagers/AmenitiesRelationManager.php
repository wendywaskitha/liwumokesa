<?php

namespace App\Filament\Resources\DestinationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class AmenitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'amenities';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Fasilitas Umum';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Fasilitas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                    ->label('Ikon')
                    ->maxLength(50)
                    ->helperText('Format: class ikon seperti "fa-wifi" atau emoji'),
                Forms\Components\Select::make('type')
                    ->label('Tipe Fasilitas')
                    ->options([
                        'umum' => 'Umum',
                        'khusus' => 'Khusus',
                        'tambahan' => 'Tambahan',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->maxLength(500),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Fasilitas')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('icon')
                    ->label('Ikon')
                    ->icon(fn (string $state): string => $state)
                    ->default('heroicon-o-check'),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'umum' => 'success',
                        'khusus' => 'warning',
                        'tambahan' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe Fasilitas')
                    ->options([
                        'umum' => 'Umum',
                        'khusus' => 'Khusus',
                        'tambahan' => 'Tambahan',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
