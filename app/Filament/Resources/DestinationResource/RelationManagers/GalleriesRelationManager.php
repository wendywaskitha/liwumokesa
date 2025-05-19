<?php

namespace App\Filament\Resources\DestinationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GalleriesRelationManager extends RelationManager
{
    protected static string $relationship = 'galleries';

    protected static ?string $recordTitleAttribute = 'caption';

    protected static ?string $title = 'Galeri Foto';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file_path')
                    ->label('Gambar')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->disk('public')
                    ->directory('gallery/destinations')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('caption')
                    ->label('Keterangan')
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_featured')
                    ->label('Tampilkan sebagai unggulan')
                    ->default(false),

                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->integer()
                    ->default(0)
                    ->helperText('Urutan tampilan gambar (0 = paling awal)'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('caption')
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('caption')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('uploadMultiple')
                    ->label('Upload Multiple')
                    ->icon('heroicon-o-photo')
                    ->form([
                        Forms\Components\FileUpload::make('images')
                            ->label('Gambar (Multiple)')
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('gallery/destinations')
                            ->maxSize(5120) // 5MB
                            ->maxFiles(10)
                            ->required(),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        foreach ($data['images'] as $image) {
                            $livewire->getOwnerRecord()->galleries()->create([
                                'file_path' => $image,
                                'caption' => 'Foto ' . $livewire->getOwnerRecord()->name,
                                'is_featured' => false,
                                'order' => 0,
                            ]);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('setAsFeatured')
                    ->label('Set Unggulan')
                    ->icon('heroicon-o-star')
                    ->action(function ($record) {
                        // Unset all featured images first
                        $record->getRelated()->update(['is_featured' => false]);
                        // Set this one as featured
                        $record->update(['is_featured' => true]);
                    })
                    ->visible(fn ($record) => !$record->is_featured),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order')
            ->reorderable('order');
    }
}
