<?php

namespace App\Filament\Resources\CreativeEconomyResource\RelationManagers;

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
                    ->required()
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('creative_economies/gallery')
                    ->columnSpanFull()
                    ->imageEditor(),

                Forms\Components\TextInput::make('caption')
                    ->label('Keterangan')
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_featured')
                    ->label('Tampilkan sebagai unggulan')
                    ->default(false)
                    ->helperText('Gambar ini akan ditampilkan di galeri utama'),

                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->helperText('Semakin kecil angka, semakin di depan posisinya'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('caption')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Unggulan'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Hanya Gambar Unggulan'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Foto'),

                Tables\Actions\Action::make('uploadMultiple')
                    ->label('Upload Multiple')
                    ->icon('heroicon-o-photo')
                    ->action(function (array $data, RelationManager $livewire) {
                        foreach ($data['images'] as $image) {
                            $livewire->getOwnerRecord()->galleries()->create([
                                'file_path' => $image,
                                'caption' => $data['caption'] ?? 'Foto ' . $livewire->getOwnerRecord()->name,
                                'is_featured' => false,
                                'order' => 0,
                            ]);
                        }
                    })
                    ->form([
                        Forms\Components\FileUpload::make('images')
                            ->label('Gambar')
                            ->multiple()
                            ->maxFiles(10)
                            ->disk('public')
                            ->directory('creative_economies/gallery')
                            ->required()
                            ->image(),

                        Forms\Components\TextInput::make('caption')
                            ->label('Keterangan (Opsional)')
                            ->maxLength(255),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setFeatured')
                        ->label('Set sebagai Unggulan')
                        ->action(fn ($records) => $records->each->update(['is_featured' => true]))
                        ->icon('heroicon-o-star'),

                    Tables\Actions\BulkAction::make('unsetFeatured')
                        ->label('Hapus dari Unggulan')
                        ->action(fn ($records) => $records->each->update(['is_featured' => false]))
                        ->icon('heroicon-o-x-mark'),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }
}
