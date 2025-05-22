<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Culinary;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CulinaryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CulinaryResource\RelationManagers;

class CulinaryResource extends Resource
{
    protected static ?string $model = Culinary::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';

    protected static ?string $navigationGroup = 'Wisata';

    protected static ?string $navigationLabel = 'Kuliner';

    protected static ?int $navigationSort = 30;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Tempat Kuliner')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('type')
                            ->label('Jenis Tempat')
                            ->options([
                                'seafood' => 'Seafood',
                                'tradisional' => 'Tradisional',
                                'kafe' => 'Kafe',
                                'cafe' => 'Cafe',
                                'restoran' => 'Restoran',
                                'warung' => 'Warung',
                                'street_food' => 'Kaki Lima',
                                'bakery' => 'Bakery'
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('district_id')
                            ->label('Kecamatan')
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Lokasi')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->maxLength(500)
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

                Forms\Components\Section::make('Detail Kuliner')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('opening_hours')
                            ->label('Jam Operasional')
                            ->placeholder('Contoh: 08:00 - 22:00')
                            ->required(),

                        Forms\Components\TextInput::make('price_range_start')
                            ->label('Harga Mulai Dari')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('price_range_end')
                            ->label('Hingga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->gt('price_range_start'),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Menu Unggulan')
                    ->schema([
                        Forms\Components\Textarea::make('featured_menu')
                            ->label('Menu Unggulan')
                            ->helperText('Masukkan menu unggulan dipisahkan dengan baris baru')
                            ->rows(5)
                            // Use dehydrateStateUsing to convert textarea content to JSON when saving
                            ->dehydrateStateUsing(function ($state) {
                                if (empty($state)) {
                                    return null;
                                }

                                if (is_string($state)) {
                                    $menuItems = array_filter(explode("\n", $state));
                                    return json_encode(array_values($menuItems));
                                }

                                return $state;
                            })
                            // Instead of hydrateStateUsing (which doesn't exist), use afterStateHydrated
                            ->afterStateHydrated(function (Forms\Components\Textarea $component, $state) {
                                if (empty($state)) {
                                    return;
                                }

                                if (is_string($state)) {
                                    $decodedMenu = json_decode($state, true);
                                    if (is_array($decodedMenu)) {
                                        $component->state(implode("\n", $decodedMenu));
                                    }
                                }
                            }),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Kontak & Info Tambahan')
                    ->schema([
                        Forms\Components\TextInput::make('contact_person')
                            ->label('Nama Kontak'),

                        Forms\Components\TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel(),

                        Forms\Components\TextInput::make('social_media')
                            ->label('Media Sosial')
                            ->placeholder('@username atau URL'),

                        Forms\Components\Toggle::make('has_vegetarian_option')
                            ->label('Tersedia Menu Vegetarian')
                            ->default(false),

                        Forms\Components\Toggle::make('halal_certified')
                            ->label('Bersertifikat Halal')
                            ->default(true),

                        Forms\Components\Toggle::make('has_delivery')
                            ->label('Layanan Pesan Antar')
                            ->default(false),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Foto Utama')
                            ->image()
                            ->directory('culinaries')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_recommended')
                            ->label('Rekomendasi')
                            ->helperText('Tandai sebagai tempat kuliner yang direkomendasikan')
                            ->default(false),

                        Forms\Components\Toggle::make('status')
                            ->label('Aktif')
                            ->helperText('Aktifkan atau nonaktifkan tempat kuliner ini')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Tempat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'seafood' => 'success',
                        'tradisional' => 'danger',
                        'kafe', 'cafe' => 'info',
                        'restoran' => 'primary',
                        'warung', 'street_food' => 'warning',
                        'bakery' => 'success',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('price_range')
                    ->label('Kisaran Harga')
                    ->getStateUsing(function ($record) {
                        return 'Rp ' . number_format($record->price_range_start, 0, ',', '.') . ' - ' . number_format($record->price_range_end, 0, ',', '.');
                    }),

                Tables\Columns\TextColumn::make('opening_hours')
                    ->label('Jam Buka'),

                Tables\Columns\IconColumn::make('is_recommended')
                    ->label('Rekomendasi')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Tempat')
                    ->options([
                        'restoran' => 'Restoran',
                        'warung' => 'Warung',
                        'kafe' => 'Kafe',
                        'rumah_makan' => 'Rumah Makan',
                        'pedagang_kaki_lima' => 'Pedagang Kaki Lima',
                        'food_court' => 'Food Court',
                    ]),

                Tables\Filters\SelectFilter::make('district')
                    ->label('Kecamatan')
                    ->relationship('district', 'name'),

                Tables\Filters\TernaryFilter::make('is_recommended')
                    ->label('Rekomendasi'),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Aktif'),

                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\TextInput::make('price_from')
                            ->label('Harga Dari')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('price_to')
                            ->label('Harga Sampai')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('map')
                    ->label('Lihat Peta')
                    ->icon('heroicon-o-map-pin')
                    ->url(fn (Culinary $record): string => "https://www.google.com/maps?q={$record->latitude},{$record->longitude}")
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setRecommended')
                        ->label('Set Rekomendasi')
                        ->icon('heroicon-o-star')
                        ->action(fn (Collection $records) => $records->each->update(['is_recommended' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('removeRecommendation')
                        ->label('Hapus Rekomendasi')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn (Collection $records) => $records->each->update(['is_recommended' => false]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('activateAll')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => $records->each->update(['status' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivateAll')
                        ->label('Nonaktifkan')
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
            'index' => Pages\ListCulinaries::route('/'),
            'create' => Pages\CreateCulinary::route('/create'),
            'view' => Pages\ViewCulinary::route('/{record}'),
            'edit' => Pages\EditCulinary::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $featuredMenu = [];

        if (!empty($record->featured_menu)) {
            $decodedMenu = is_string($record->featured_menu)
                ? json_decode($record->featured_menu, true)
                : $record->featured_menu;

            if (is_array($decodedMenu)) {
                $featuredMenu = $decodedMenu;
            }
        }

        return [
            'Kategori' => $record->type,
            'Lokasi' => $record->address,
            'Menu Unggulan' => is_array($featuredMenu)
                ? implode(", ", $featuredMenu)
                : 'Tidak ada menu unggulan',
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'address', 'description', 'district.name'];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                request()->input('type'),
                fn (Builder $query, $type) => $query->where('type', $type)
            );
    }
}
