<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Gallery;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GalleryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Galeri Foto';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('imageable_type')
                    ->label('Tipe Entitas')
                    ->options([
                        'App\Models\Destination' => 'Destinasi',
                        'App\Models\Accommodation' => 'Akomodasi',
                        'App\Models\Transportation' => 'Transportasi',
                        'App\Models\Culinary' => 'Kuliner',
                        'App\Models\CreativeEconomy' => 'Ekonomi Kreatif',
                        'App\Models\CulturalHeritage' => 'Warisan Budaya',
                        'App\Models\Event' => 'Event',
                        'App\Models\TravelPackage' => 'Paket Wisata',
                        'App\Models\District' => 'Kecamatan',
                    ])
                    ->required()
                    ->reactive(),

                Forms\Components\Select::make('imageable_id')
                    ->label('Entitas')
                    ->options(function (callable $get) {
                        $type = $get('imageable_type');

                        if (!$type) {
                            return [];
                        }

                        $model = new $type;

                        return $model::pluck('name', 'id')->toArray();
                    })
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\FileUpload::make('file_path')
                    ->label('Gambar')
                    ->image()
                    ->required()
                    ->maxSize(2048) // 2MB
                    ->disk('public')
                    ->directory('gallery')
                    ->columnSpanFull()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),

                Forms\Components\TextInput::make('caption')
                    ->label('Keterangan')
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_featured')
                    ->label('Tampilkan sebagai unggulan')
                    ->default(false)
                    ->helperText('Gambar akan ditampilkan di halaman utama atau bagian unggulan'),

                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->helperText('Urutan penampilan gambar (0 = paling awal)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->square()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('imageable_type')
                    ->label('Tipe Entitas')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'App\Models\Destination' => 'Destinasi',
                        'App\Models\Accommodation' => 'Akomodasi',
                        'App\Models\Transportation' => 'Transportasi',
                        'App\Models\Culinary' => 'Kuliner',
                        'App\Models\CreativeEconomy' => 'Ekonomi Kreatif',
                        'App\Models\CulturalHeritage' => 'Warisan Budaya',
                        'App\Models\Event' => 'Event',
                        'App\Models\TravelPackage' => 'Paket Wisata',
                        'App\Models\District' => 'Kecamatan',
                        default => class_basename($state),
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('imageable.name')
                    ->label('Nama Entitas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('caption')
                    ->label('Keterangan')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Unggulan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('imageable_type')
                    ->label('Tipe Entitas')
                    ->options([
                        'App\Models\Destination' => 'Destinasi',
                        'App\Models\Accommodation' => 'Akomodasi',
                        'App\Models\Transportation' => 'Transportasi',
                        'App\Models\Culinary' => 'Kuliner',
                        'App\Models\CreativeEconomy' => 'Ekonomi Kreatif',
                        'App\Models\CulturalHeritage' => 'Warisan Budaya',
                        'App\Models\Event' => 'Event',
                        'App\Models\TravelPackage' => 'Paket Wisata',
                        'App\Models\District' => 'Kecamatan',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Gambar Unggulan'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Gallery $record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setFeatured')
                        ->label('Set sebagai Unggulan')
                        ->icon('heroicon-o-star')
                        ->action(fn ($records) => $records->each->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('unsetFeatured')
                        ->label('Hapus dari Unggulan')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->update(['is_featured' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'view' => Pages\ViewGallery::route('/{record}'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }

    // Tambahan untuk search global
    public static function getGloballySearchableAttributes(): array
    {
        return ['caption', 'imageable.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Entitas' => $record->imageable->name ?? 'N/A',
            'Tipe' => match ($record->imageable_type) {
                'App\Models\Destination' => 'Destinasi',
                'App\Models\Accommodation' => 'Akomodasi',
                'App\Models\Transportation' => 'Transportasi',
                'App\Models\Culinary' => 'Kuliner',
                'App\Models\CreativeEconomy' => 'Ekonomi Kreatif',
                'App\Models\CulturalHeritage' => 'Warisan Budaya',
                'App\Models\Event' => 'Event',
                'App\Models\TravelPackage' => 'Paket Wisata',
                'App\Models\District' => 'Kecamatan',
                default => class_basename($record->imageable_type),
            },
        ];
    }

    // Tambahan untuk getEloquentQuery agar tidak error saat relasi tidak ada
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('imageable');
    }
}
