<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Amenity;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AmenityResource\Pages;
use App\Filament\Resources\AmenityResource\RelationManagers;

class AmenityResource extends Resource
{
    protected static ?string $model = Amenity::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Fasilitas Umum';
    protected static ?string $navigationGroup = 'Infrastruktur';
    protected static ?int $navigationSort = 25;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Dasar')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Fasilitas')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) =>
                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug URL')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Amenity::class, 'slug', ignoreRecord: true)
                                    ->disabled(fn (string $operation) => $operation === 'edit'),

                                Forms\Components\Select::make('type')
                                    ->label('Jenis Fasilitas')
                                    ->options([
                                        'toilet' => 'Toilet Umum',
                                        'atm' => 'ATM / Bank',
                                        'health' => 'Fasilitas Kesehatan',
                                        'worship' => 'Tempat Ibadah',
                                        'information' => 'Pusat Informasi',
                                        'market' => 'Pasar / Toko',
                                        'parking' => 'Area Parkir',
                                        'gas_station' => 'SPBU',
                                        'rest_area' => 'Rest Area',
                                        'other' => 'Lainnya',
                                    ])
                                    ->required()
                                    ->searchable(),

                                Forms\Components\Select::make('district_id')
                                    ->label('Kecamatan')
                                    ->relationship('district', 'name')
                                    ->required()
                                    ->preload()
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Kecamatan')
                                            ->required()
                                            ->maxLength(255),
                                    ]),

                                Forms\Components\TextInput::make('contact')
                                    ->label('Kontak')
                                    ->maxLength(50),

                                Forms\Components\Textarea::make('address')
                                    ->label('Alamat Lengkap')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Informasi Lokasi')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('latitude')
                                            ->label('Latitude')
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->minValue(-90)
                                            ->maxValue(90)
                                            ->step(0.000001),

                                        Forms\Components\TextInput::make('longitude')
                                            ->label('Longitude')
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->minValue(-180)
                                            ->maxValue(180)
                                            ->step(0.000001),
                                    ]),

                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Foto Fasilitas')
                                    ->image()
                                    ->directory('amenities')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Jam Operasional')
                            ->schema([
                                Forms\Components\Select::make('availability')
                                    ->label('Ketersediaan')
                                    ->options([
                                        '24_hours' => '24 Jam',
                                        'custom' => 'Jam Tertentu',
                                        'closed' => 'Tutup Sementara',
                                    ])
                                    ->default('custom')
                                    ->required()
                                    ->reactive(),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('opening_hours')
                                            ->label('Jam Buka')
                                            ->placeholder('08:00')
                                            ->maxLength(10)
                                            ->visible(fn (Forms\Get $get) => $get('availability') === 'custom'),

                                        Forms\Components\TextInput::make('closing_hours')
                                            ->label('Jam Tutup')
                                            ->placeholder('17:00')
                                            ->maxLength(10)
                                            ->visible(fn (Forms\Get $get) => $get('availability') === 'custom'),
                                    ]),

                                Forms\Components\Textarea::make('operational_notes')
                                    ->label('Catatan Operasional')
                                    ->placeholder('Masukkan catatan khusus, misalnya: tutup setiap hari Minggu')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Tambahan')
                            ->schema([
                                Forms\Components\Toggle::make('is_free')
                                    ->label('Gratis')
                                    ->default(true)
                                    ->reactive()
                                    ->helperText('Apakah fasilitas ini gratis untuk digunakan?'),

                                Forms\Components\TextInput::make('fee')
                                    ->label('Biaya')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->visible(fn (Forms\Get $get) => $get('is_free') === false),

                                Forms\Components\Toggle::make('is_accessible')
                                    ->label('Ramah Difabel')
                                    ->default(false)
                                    ->helperText('Apakah fasilitas ini ramah untuk difabel?'),

                                Forms\Components\Toggle::make('status')
                                    ->label('Status Aktif')
                                    ->default(true)
                                    ->helperText('Status aktif menentukan apakah fasilitas ditampilkan'),
                            ]),

                        Forms\Components\Section::make('Fasilitas Tersedia')
                            ->schema([
                                Forms\Components\CheckboxList::make('features')
                                    ->label('')
                                    ->options([
                                        'parking' => 'Parkir',
                                        'wifi' => 'WiFi',
                                        'toilet' => 'Toilet',
                                        'air_conditioner' => 'AC',
                                        'charging' => 'Pengisian Daya',
                                        'waiting_room' => 'Ruang Tunggu',
                                        'security' => 'Keamanan',
                                    ])
                                    ->columns(2),

                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->placeholder('Deskripsi singkat tentang fasilitas ini')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Fasilitas')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'toilet' => 'Toilet Umum',
                        'atm' => 'ATM / Bank',
                        'health' => 'Fasilitas Kesehatan',
                        'worship' => 'Tempat Ibadah',
                        'information' => 'Pusat Informasi',
                        'market' => 'Pasar / Toko',
                        'parking' => 'Area Parkir',
                        'gas_station' => 'SPBU',
                        'rest_area' => 'Rest Area',
                        'other' => 'Lainnya',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'toilet',
                        'success' => 'atm',
                        'warning' => 'health',
                        'info' => 'worship',
                        'secondary' => 'information',
                        'danger' => 'market',
                        'gray' => 'other',
                    ]),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-currency-dollar')
                    ->trueColor('success')
                    ->falseColor('warning'),

                Tables\Columns\IconColumn::make('is_accessible')
                    ->label('Ramah Difabel')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'toilet' => 'Toilet Umum',
                        'atm' => 'ATM / Bank',
                        'health' => 'Fasilitas Kesehatan',
                        'worship' => 'Tempat Ibadah',
                        'information' => 'Pusat Informasi',
                        'market' => 'Pasar / Toko',
                        'parking' => 'Area Parkir',
                        'gas_station' => 'SPBU',
                        'rest_area' => 'Rest Area',
                        'other' => 'Lainnya',
                    ]),

                Tables\Filters\SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name'),

                Tables\Filters\SelectFilter::make('availability')
                    ->label('Ketersediaan')
                    ->options([
                        '24_hours' => '24 Jam',
                        'custom' => 'Jam Tertentu',
                        'closed' => 'Tutup Sementara',
                    ]),

                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('Gratis'),

                Tables\Filters\TernaryFilter::make('is_accessible')
                    ->label('Ramah Difabel'),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Aktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activateAmenities')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => $records->each->update(['status' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivateAmenities')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Collection $records) => $records->each->update(['status' => false]))
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Fasilitas')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nama Fasilitas')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('type')
                                        ->label('Jenis Fasilitas')
                                        ->badge()
                                        ->formatStateUsing(fn (string $state): string => match($state) {
                                            'toilet' => 'Toilet Umum',
                                            'atm' => 'ATM / Bank',
                                            'health' => 'Fasilitas Kesehatan',
                                            'worship' => 'Tempat Ibadah',
                                            'information' => 'Pusat Informasi',
                                            'market' => 'Pasar / Toko',
                                            'parking' => 'Area Parkir',
                                            'gas_station' => 'SPBU',
                                            'rest_area' => 'Rest Area',
                                            'other' => 'Lainnya',
                                            default => $state,
                                        })
                                        ->color(fn (string $state): string => match($state) {
                                            'toilet' => 'primary',
                                            'atm' => 'success',
                                            'health' => 'warning',
                                            'worship' => 'info',
                                            'information' => 'secondary',
                                            'market' => 'danger',
                                            'other' => 'gray',
                                            default => 'gray',
                                        }),

                                    Infolists\Components\TextEntry::make('district.name')
                                        ->label('Kecamatan')
                                        ->icon('heroicon-o-map'),
                                ]),

                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('availability')
                                        ->label('Jam Operasional')
                                        ->formatStateUsing(function ($state, $record) {
                                            switch ($state) {
                                                case '24_hours':
                                                    return 'Buka 24 jam';
                                                case 'custom':
                                                    return $record->opening_hours . ' - ' . $record->closing_hours;
                                                case 'closed':
                                                    return 'Tutup Sementara';
                                                default:
                                                    return $state;
                                            }
                                        })
                                        ->icon('heroicon-o-clock'),

                                    Infolists\Components\TextEntry::make('operational_notes')
                                        ->label('Catatan')
                                        ->visible(fn ($record) => !empty($record->operational_notes)),
                                ]),
                            ]),

                        Infolists\Components\TextEntry::make('address')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Grid::make(2)
                    ->schema([
                        Infolists\Components\Section::make('Detail Fasilitas')
                            ->schema([
                                // Mengganti IconEntry dengan TextEntry yang memiliki ikon
                                Infolists\Components\TextEntry::make('is_free')
                                    ->label('Biaya')
                                    ->formatStateUsing(function ($state, $record) {
                                        if ($state) return 'Gratis';
                                        return 'Berbayar: Rp ' . number_format($record->fee, 0, ',', '.');
                                    })
                                    ->icon(fn (bool $state) => $state ? 'heroicon-o-check' : 'heroicon-o-currency-dollar')
                                    ->color(fn (bool $state) => $state ? 'success' : 'warning'),

                                // Mengganti IconEntry dengan TextEntry yang memiliki ikon
                                Infolists\Components\TextEntry::make('is_accessible')
                                    ->label('Aksesibilitas')
                                    ->formatStateUsing(fn (bool $state) => $state ? 'Ramah Difabel' : 'Standar')
                                    ->icon(fn (bool $state) => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                                    ->color(fn (bool $state) => $state ? 'success' : 'gray'),

                                Infolists\Components\TextEntry::make('contact')
                                    ->label('Kontak')
                                    ->icon('heroicon-o-phone')
                                    ->visible(fn ($record) => !empty($record->contact)),

                                Infolists\Components\TextEntry::make('features')
                                    ->label('Fasilitas Tersedia')
                                    ->listWithLineBreaks()
                                    ->bulleted()
                                    ->formatStateUsing(function ($state) {
                                        if (empty($state)) return [];

                                        $labels = [
                                            'parking' => 'Parkir',
                                            'wifi' => 'WiFi',
                                            'toilet' => 'Toilet',
                                            'air_conditioner' => 'AC',
                                            'charging' => 'Pengisian Daya',
                                            'waiting_room' => 'Ruang Tunggu',
                                            'security' => 'Keamanan',
                                        ];

                                        if (is_array($state)) {
                                            return array_map(fn ($item) => $labels[$item] ?? $item, $state);
                                        }

                                        return $state;
                                    }),
                            ]),

                        Infolists\Components\Section::make('Lokasi')
                            ->schema([
                                Infolists\Components\ImageEntry::make('featured_image')
                                    ->label('Foto Fasilitas')
                                    ->visible(fn ($record) => $record->featured_image !== null),

                                Infolists\Components\TextEntry::make('coordinates')
                                    ->label('Koordinat')
                                    ->state(function ($record) {
                                        return $record->latitude . ', ' . $record->longitude;
                                    })
                                    ->copyable()
                                    ->icon('heroicon-o-map-pin'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Deskripsi')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->label('')
                            ->markdown(),
                    ])
                    ->visible(fn ($record) => !empty($record->description))
                    ->collapsible(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            RelationManagers\DistrictRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAmenities::route('/'),
            'create' => Pages\CreateAmenity::route('/create'),
            'view' => Pages\ViewAmenity::route('/{record}'),
            'edit' => Pages\EditAmenity::route('/{record}/edit'),
        ];
    }
}
