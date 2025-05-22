<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use App\Models\District;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\CulturalHeritage;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CulturalHeritageResource\Pages;
use App\Filament\Resources\CulturalHeritageResource\RelationManagers;

class CulturalHeritageResource extends Resource
{
    protected static ?string $model = CulturalHeritage::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = 'Warisan Budaya';

    protected static ?string $navigationGroup = 'Wisata';

    protected static ?int $navigationSort = 50;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, callable $set) =>
                                $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(CulturalHeritage::class, 'slug', fn ($record) => $record),

                        Forms\Components\Select::make('type')
                            ->label('Jenis')
                            ->options([
                                'tangible' => 'Warisan Budaya Berwujud',
                                'intangible' => 'Warisan Budaya Tak Berwujud',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('historical_significance')
                            ->label('Signifikansi Sejarah')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->directory('cultural-heritages')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Lokasi')
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255),

                        Forms\Components\Select::make('district_id')
                            ->label('Kecamatan')
                            ->options(District::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->step(0.0000001),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->step(0.0000001),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status dan Pengakuan')
                    ->schema([
                        Forms\Components\Select::make('conservation_status')
                            ->label('Status Konservasi')
                            ->options([
                                'excellent' => 'Sangat Baik',
                                'good' => 'Baik',
                                'fair' => 'Cukup',
                                'poor' => 'Buruk',
                                'critical' => 'Kritis',
                                'unknown' => 'Tidak Diketahui',
                            ]),

                        Forms\Components\Select::make('recognition_status')
                            ->label('Status Pengakuan')
                            ->options([
                                'local' => 'Lokal',
                                'regional' => 'Regional',
                                'national' => 'Nasional',
                                'international' => 'Internasional',
                                'unesco' => 'UNESCO',
                            ]),

                        Forms\Components\DatePicker::make('recognition_date')
                            ->label('Tanggal Pengakuan')
                            ->format('d/m/Y'),

                        Forms\Components\Toggle::make('is_endangered')
                            ->label('Terancam Punah')
                            ->default(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Placeholder::make('type_specific_info')
                            ->label(function (callable $get) {
                                return $get('type') === 'tangible'
                                    ? 'Informasi Warisan Budaya Berwujud'
                                    : 'Informasi Warisan Budaya Tak Berwujud';
                            }),

                        Forms\Components\RichEditor::make('physical_description')
                            ->label('Deskripsi Fisik')
                            ->visible(fn (callable $get) => $get('type') === 'tangible')
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('practices_description')
                            ->label('Deskripsi Praktik')
                            ->visible(fn (callable $get) => $get('type') === 'intangible')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('custodian')
                            ->label('Pemelihara/Penjaga')
                            ->maxLength(255),

                        Forms\Components\RichEditor::make('visitor_info')
                            ->label('Informasi Pengunjung')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Pengaturan Publikasi')
                    ->schema([
                        Forms\Components\Toggle::make('allows_visits')
                            ->label('Dapat Dikunjungi')
                            ->default(true),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tampilkan Sebagai Unggulan')
                            ->default(false),

                        Forms\Components\Toggle::make('status')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Nonaktif berarti tidak ditampilkan di aplikasi'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tangible' => 'Berwujud',
                        'intangible' => 'Tak Berwujud',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'tangible' => 'success',
                        'intangible' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('conservation_status')
                    ->label('Konservasi')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'excellent' => 'Sangat Baik',
                        'good' => 'Baik',
                        'fair' => 'Cukup',
                        'poor' => 'Buruk',
                        'critical' => 'Kritis',
                        'unknown' => 'Tidak Diketahui',
                        null => '-',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'excellent' => 'success',
                        'good' => 'success',
                        'fair' => 'warning',
                        'poor' => 'danger',
                        'critical' => 'danger',
                        'unknown' => 'gray',
                        null => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_endangered')
                    ->label('Terancam')
                    ->boolean()
                    ->sortable()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'tangible' => 'Warisan Budaya Berwujud',
                        'intangible' => 'Warisan Budaya Tak Berwujud',
                    ]),

                Tables\Filters\SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name'),

                Tables\Filters\SelectFilter::make('conservation_status')
                    ->label('Status Konservasi')
                    ->options([
                        'excellent' => 'Sangat Baik',
                        'good' => 'Baik',
                        'fair' => 'Cukup',
                        'poor' => 'Buruk',
                        'critical' => 'Kritis',
                        'unknown' => 'Tidak Diketahui',
                    ]),

                Tables\Filters\TernaryFilter::make('is_endangered')
                    ->label('Terancam Punah'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Unggulan'),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAsFeatured')
                        ->label('Jadikan Unggulan')
                        ->icon('heroicon-o-star')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('removeFromFeatured')
                        ->label('Hapus dari Unggulan')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => false]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('activateRecords')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => $records->each->update(['status' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivateRecords')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Collection $records) => $records->each->update(['status' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Utama')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nama')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                        Infolists\Components\TextEntry::make('type')
                            ->label('Jenis')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'tangible' => 'Warisan Budaya Berwujud',
                                'intangible' => 'Warisan Budaya Tak Berwujud',
                                default => $state,
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'tangible' => 'success',
                                'intangible' => 'info',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('conservation_status')
                            ->label('Status Konservasi')
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'excellent' => 'Sangat Baik',
                                'good' => 'Baik',
                                'fair' => 'Cukup',
                                'poor' => 'Buruk',
                                'critical' => 'Kritis',
                                'unknown' => 'Tidak Diketahui',
                                null => '-',
                                default => $state,
                            })
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'excellent' => 'success',
                                'good' => 'success',
                                'fair' => 'warning',
                                'poor' => 'danger',
                                'critical' => 'danger',
                                'unknown' => 'gray',
                                null => 'gray',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('recognition_status')
                            ->label('Status Pengakuan')
                            ->badge()
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'local' => 'Lokal',
                                'regional' => 'Regional',
                                'national' => 'Nasional',
                                'international' => 'Internasional',
                                'unesco' => 'UNESCO',
                                null => '-',
                                default => $state,
                            })
                            ->color(fn ($state) => match ($state) {
                                'unesco' => 'success',
                                'international' => 'success',
                                'national' => 'primary',
                                'regional' => 'warning',
                                'local' => 'gray',
                                null => 'gray',
                                default => 'gray',
                            }),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\ImageEntry::make('featured_image')
                            ->label('Gambar Utama')
                            ->disk('public')
                            ->height(400)
                            ->visible(fn ($record) => $record->featured_image)
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Deskripsi')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->label(false)
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Signifikansi Sejarah')
                    ->schema([
                        Infolists\Components\TextEntry::make('historical_significance')
                            ->label(false)
                            ->markdown()
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->historical_significance)),
                    ]),

                Infolists\Components\Section::make(function ($record) {
                    return $record->type === 'tangible' ? 'Deskripsi Fisik' : 'Deskripsi Praktik';
                })
                    ->schema([
                        Infolists\Components\TextEntry::make('physical_description')
                            ->label(false)
                            ->markdown()
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->type === 'tangible' && !empty($record->physical_description)),

                        Infolists\Components\TextEntry::make('practices_description')
                            ->label(false)
                            ->markdown()
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->type === 'intangible' && !empty($record->practices_description)),
                    ]),

                Infolists\Components\Section::make('Lokasi')
                    ->schema([
                        Infolists\Components\TextEntry::make('district.name')
                            ->label('Kecamatan'),

                        Infolists\Components\TextEntry::make('location')
                            ->label('Lokasi Detail'),

                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('latitude')
                                    ->label('Latitude')
                                    ->numeric(6),

                                Infolists\Components\TextEntry::make('longitude')
                                    ->label('Longitude')
                                    ->numeric(6),
                            ])
                            ->columns(2)
                            ->visible(fn ($record) => $record->latitude && $record->longitude),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Peta Lokasi')
                    ->schema([
                        Infolists\Components\ViewEntry::make('map')
                            ->view('filament.infolists.components.map-viewer')
                            ->state(fn ($record) => [
                                'latitude' => $record->latitude,
                                'longitude' => $record->longitude,
                                'name' => $record->name,
                                'address' => $record->location,
                            ])
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->latitude && $record->longitude),
                    ]),

                Infolists\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Infolists\Components\TextEntry::make('custodian')
                            ->label('Pemelihara/Penjaga')
                            ->visible(fn ($record) => !empty($record->custodian)),

                        Infolists\Components\TextEntry::make('recognition_date')
                            ->label('Tanggal Pengakuan')
                            ->date('d F Y')
                            ->visible(fn ($record) => !empty($record->recognition_date)),

                        Infolists\Components\IconEntry::make('is_endangered')
                            ->label('Terancam Punah')
                            ->boolean()
                            ->trueIcon('heroicon-o-exclamation-triangle')
                            ->falseIcon('heroicon-o-check-circle')
                            ->trueColor('danger')
                            ->falseColor('success'),

                        Infolists\Components\IconEntry::make('allows_visits')
                            ->label('Dapat Dikunjungi')
                            ->boolean(),

                        Infolists\Components\TextEntry::make('visitor_info')
                            ->label('Informasi Pengunjung')
                            ->markdown()
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->visitor_info)),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Status')
                    ->schema([
                        Infolists\Components\IconEntry::make('is_featured')
                            ->label('Ditampilkan Sebagai Unggulan')
                            ->boolean(),

                        Infolists\Components\IconEntry::make('status')
                            ->label('Status Aktif')
                            ->boolean(),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d F Y, H:i'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d F Y, H:i'),
                    ])
                    ->columns(4),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GalleriesRelationManager::class,
            RelationManagers\EventsRelationManager::class,
            RelationManagers\AmenitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCulturalHeritages::route('/'),
            'create' => Pages\CreateCulturalHeritage::route('/create'),
            'edit' => Pages\EditCulturalHeritage::route('/{record}/edit'),
            // 'view' => Pages\ViewCulturalHeritage::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Jenis' => $record->type === 'tangible' ? 'Berwujud' : 'Tak Berwujud',
            'Kecamatan' => $record->district?->name ?? '-'
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description', 'historical_significance', 'location'];
    }
}
