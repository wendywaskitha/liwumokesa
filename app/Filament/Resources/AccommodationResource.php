<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Accommodation;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AccommodationResource\Pages;
use App\Filament\Resources\AccommodationResource\RelationManagers;

class AccommodationResource extends Resource
{
    protected static ?string $model = Accommodation::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Wisata';

    protected static ?string $navigationLabel = 'Akomodasi';

    protected static ?int $navigationSort = 10;

    protected static ?int $shouldRegisterNavigation = true;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akomodasi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Akomodasi')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug URL')
                            ->helperText('Akan diisi otomatis jika dikosongkan')
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('Jenis Akomodasi')
                            ->options([
                                'hotel' => 'Hotel',
                                'homestay' => 'Homestay',
                                'guest house' => 'Guest House',
                                'villa' => 'Villa',
                                'cottage' => 'Cottage',
                            ])
                            ->required(),

                        Forms\Components\Select::make('district_id')
                            ->label('Kecamatan')
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Akomodasi')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('address')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->required(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Harga & Fasilitas')
                    ->schema([
                        Forms\Components\TextInput::make('price_range_start')
                            ->label('Mulai Dari')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('price_range_end')
                            ->label('Hingga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->gt('price_range_start'),

                        Forms\Components\CheckboxList::make('facilities')
                            ->label('Fasilitas')
                            ->options([
                                'wifi' => 'WiFi Gratis',
                                'parking' => 'Parkir',
                                'ac' => 'AC',
                                'restaurant' => 'Restoran',
                                'swimming_pool' => 'Kolam Renang',
                                'laundry' => 'Layanan Laundry',
                                'breakfast' => 'Sarapan',
                                'meeting_room' => 'Ruang Pertemuan',
                                'spa' => 'Spa',
                                'gym' => 'Gym/Fitness',
                                'beach_access' => 'Akses Pantai',
                                'pet_friendly' => 'Pet Friendly',
                                'airport_shuttle' => 'Antar-Jemput Bandara',
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('contact_person')
                            ->label('Nama Kontak'),

                        Forms\Components\TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel(),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email(),

                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->prefix('https://'),

                        Forms\Components\TextInput::make('booking_link')
                            ->label('Link Booking')
                            ->url()
                            ->prefix('https://'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Foto Utama')
                            ->image()
                            ->directory('accommodations')
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
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('price_range_start')
                    ->label('Harga Mulai')
                    ->sortable()
                    ->money('IDR', true),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->label('Jenis Akomodasi')
                    ->options([
                        'hotel' => 'Hotel',
                        'homestay' => 'Homestay',
                        'guest house' => 'Guest House',
                        'villa' => 'Villa',
                        'cottage' => 'Cottage',
                    ]),

                Tables\Filters\SelectFilter::make('district')
                    ->label('Kecamatan')
                    ->relationship('district', 'name'),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Aktif'),

                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\TextInput::make('price_from')
                            ->label('Harga Dari')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('price_to')
                            ->label('Harga Hingga')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['price_from'],
                                fn (Builder $query, $price): Builder => $query->where('price_range_start', '>=', $price),
                            )
                            ->when(
                                $data['price_to'],
                                fn (Builder $query, $price): Builder => $query->where('price_range_end', '<=', $price),
                            );
                    }),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('map')
                    ->label('Lihat Peta')
                    ->icon('heroicon-o-map-pin')
                    ->url(fn (Accommodation $record): string => "https://www.google.com/maps?q={$record->latitude},{$record->longitude}")
                    ->openUrlInNewTab(),
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
            // RelationManagers\GalleriesRelationManager::class,
            // RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccommodations::route('/'),
            'create' => Pages\CreateAccommodation::route('/create'),
            // 'view' => Pages\ViewAccommodation::route('/{record}'),
            'edit' => Pages\EditAccommodation::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Jenis' => ucfirst($record->type),
            'Kecamatan' => $record->district->name,
            'Harga' => 'Rp ' . number_format($record->price_range_start, 0, ',', '.') . ' - ' . number_format($record->price_range_end, 0, ',', '.'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'address', 'district.name'];
    }
}
