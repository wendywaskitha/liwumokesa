<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transportation;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TransportationResource\Pages;
use App\Filament\Resources\TransportationResource\RelationManagers;

class TransportationResource extends Resource
{
    protected static ?string $model = Transportation::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Wisata';

    protected static ?string $navigationLabel = 'Transportasi';

    protected static ?int $navigationSort = 20;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transportasi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Transportasi/Operator')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('Jenis Transportasi')
                            ->options([
                                'darat' => 'Darat',
                                'laut' => 'Laut',
                                'udara' => 'Udara',
                            ])
                            ->required()
                            ->reactive(),

                        Forms\Components\Select::make('subtype')
                            ->label('Sub Jenis')
                            ->options(function (callable $get) {
                                $type = $get('type');

                                if ($type === 'darat') {
                                    return [
                                        'mobil' => 'Mobil/Taxi',
                                        'motor' => 'Sepeda Motor',
                                        'bus' => 'Bus/Mini Bus',
                                        'angkot' => 'Angkutan Kota',
                                        'becak' => 'Becak/Bentor',
                                        'rental' => 'Rental Kendaraan',
                                    ];
                                } elseif ($type === 'laut') {
                                    return [
                                        'kapal_cepat' => 'Kapal Cepat',
                                        'kapal_feri' => 'Kapal Feri',
                                        'perahu' => 'Perahu Tradisional',
                                        'speedboat' => 'Speedboat',
                                        'yacht' => 'Yacht/Kapal Pesiar',
                                    ];
                                } elseif ($type === 'udara') {
                                    return [
                                        'pesawat' => 'Pesawat Komersial',
                                        'charter' => 'Charter Pesawat',
                                        'helikopter' => 'Helikopter',
                                    ];
                                }

                                return [];
                            })
                            ->required(),

                        Forms\Components\Select::make('district_id')
                            ->label('Kecamatan')
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Transportasi')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('capacity')
                            ->label('Kapasitas Penumpang')
                            ->numeric()
                            ->required(),

                        Forms\Components\Select::make('price_scheme')
                            ->label('Skema Harga')
                            ->options([
                                'per_orang' => 'Per Orang',
                                'per_kendaraan' => 'Per Kendaraan',
                                'per_jam' => 'Per Jam',
                                'per_hari' => 'Per Hari',
                                'per_rute' => 'Per Rute',
                                'negosiasi' => 'Negosiasi',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('base_price')
                            ->label('Harga Dasar')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Rute & Jadwal')
                    ->schema([
                        Forms\Components\Repeater::make('routes')
                            ->label('Rute Perjalanan')
                            ->schema([
                                Forms\Components\TextInput::make('origin')
                                    ->label('Asal')
                                    ->required(),

                                Forms\Components\TextInput::make('destination')
                                    ->label('Tujuan')
                                    ->required(),

                                Forms\Components\TextInput::make('distance')
                                    ->label('Jarak (km)')
                                    ->numeric(),

                                Forms\Components\TextInput::make('duration')
                                    ->label('Durasi (Menit)')
                                    ->numeric(),

                                Forms\Components\TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\Select::make('schedule_type')
                                    ->label('Jenis Jadwal')
                                    ->options([
                                        'regular' => 'Reguler (Terjadwal)',
                                        'on_demand' => 'Sesuai Permintaan',
                                    ])
                                    ->default('regular')
                                    ->reactive(),

                                Forms\Components\Repeater::make('schedules')
                                    ->label('Jadwal Keberangkatan')
                                    ->schema([
                                        Forms\Components\Select::make('day')
                                            ->label('Hari')
                                            ->options([
                                                'monday' => 'Senin',
                                                'tuesday' => 'Selasa',
                                                'wednesday' => 'Rabu',
                                                'thursday' => 'Kamis',
                                                'friday' => 'Jumat',
                                                'saturday' => 'Sabtu',
                                                'sunday' => 'Minggu',
                                                'daily' => 'Setiap Hari',
                                            ])
                                            ->required(),

                                        Forms\Components\TimePicker::make('departure_time')
                                            ->label('Jam Keberangkatan')
                                            ->seconds(false)
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->visible(fn (callable $get) => $get('schedule_type') === 'regular'),
                            ])
                            ->columns(3)
                            ->collapsible(),
                    ]),

                Forms\Components\Section::make('Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('contact_person')
                            ->label('Nama Kontak Person'),

                        Forms\Components\TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel(),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email(),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Foto Utama')
                            ->image()
                            ->directory('transportations')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'darat' => 'Darat',
                        'laut' => 'Laut',
                        'udara' => 'Udara',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'darat',
                        'success' => 'laut',
                        'warning' => 'udara',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtype')
                    ->label('Sub Jenis')
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->searchable(),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga Dasar')
                    ->sortable()
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Transportasi')
                    ->options([
                        'darat' => 'Darat',
                        'laut' => 'Laut',
                        'udara' => 'Udara',
                    ]),

                Tables\Filters\SelectFilter::make('district')
                    ->label('Kecamatan')
                    ->relationship('district', 'name'),

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
                    Tables\Actions\BulkAction::make('activateAll')
                        ->label('Aktifkan Semua')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => $records->each->update(['status' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivateAll')
                        ->label('Nonaktifkan Semua')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Collection $records) => $records->each->update(['status' => false]))
                        ->requiresConfirmation()
                        ->color('danger'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GalleriesRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransportations::route('/'),
            'create' => Pages\CreateTransportation::route('/create'),
            'view' => Pages\ViewTransportation::route('/{record}'),
            'edit' => Pages\EditTransportation::route('/{record}/edit'),
        ];
    }

    // Tambahan untuk pencarian global
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'subtype', 'description'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Jenis' => ucfirst($record->type) . ' - ' . ucwords(str_replace('_', ' ', $record->subtype)),
            'Kecamatan' => $record->district->name,
            'Kapasitas' => $record->capacity . ' penumpang',
        ];
    }
}
