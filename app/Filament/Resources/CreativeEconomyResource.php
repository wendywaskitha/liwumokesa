<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CreativeEconomy;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CreativeEconomyResource\Pages;
use App\Filament\Resources\CreativeEconomyResource\RelationManagers;

class CreativeEconomyResource extends Resource
{
    protected static ?string $model = CreativeEconomy::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Wisata';

    protected static ?string $navigationLabel = 'Ekonomi Kreatif';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_featured', true)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Utama')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama UMKM/Usaha')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->maxLength(255)
                                    ->helperText('Akan diisi otomatis jika dikosongkan'),

                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->options(Category::where('type', 'ekonomi-kreatif')->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Kategori')
                                            ->required(),

                                        Forms\Components\Hidden::make('type')
                                            ->default('ekonomi-kreatif'),

                                        Forms\Components\Textarea::make('description')
                                            ->label('Deskripsi')
                                            ->maxLength(500),
                                    ]),

                                Forms\Components\Textarea::make('short_description')
                                    ->label('Deskripsi Singkat')
                                    ->helperText('Maksimal 300 karakter')
                                    ->maxLength(300),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi Lengkap')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('creative_economies/attachments')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Lokasi & Kontak')
                            ->schema([
                                Forms\Components\TextInput::make('address')
                                    ->label('Alamat')
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('district_id')
                                    ->label('Kecamatan')
                                    ->relationship('district', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('-4.9756'),

                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('122.4932'),

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

                                Forms\Components\TextInput::make('social_media')
                                    ->label('Media Sosial')
                                    ->placeholder('@username_instagram'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Informasi Usaha')
                            ->schema([
                                Forms\Components\TextInput::make('business_hours')
                                    ->label('Jam Operasional')
                                    ->placeholder('08:00-17:00, Senin-Sabtu'),

                                Forms\Components\TextInput::make('owner_name')
                                    ->label('Nama Pemilik/Pengelola'),

                                Forms\Components\TextInput::make('establishment_year')
                                    ->label('Tahun Berdiri')
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(date('Y')),

                                Forms\Components\TextInput::make('employees_count')
                                    ->label('Jumlah Pekerja')
                                    ->numeric(),

                                Forms\Components\TextInput::make('price_range_start')
                                    ->label('Harga Mulai Dari')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('price_range_end')
                                    ->label('Harga Hingga')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->gt('price_range_start'),

                                Forms\Components\Textarea::make('products_description')
                                    ->label('Deskripsi Produk')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible(),

                        Forms\Components\Section::make('Ketersediaan Workshop')
                            ->schema([
                                Forms\Components\Toggle::make('has_workshop')
                                    ->label('Menyediakan Workshop')
                                    ->default(false)
                                    ->reactive(),

                                Forms\Components\Textarea::make('workshop_information')
                                    ->label('Informasi Workshop')
                                    ->placeholder('Deskripsi, durasi, harga, dll')
                                    ->columnSpanFull()
                                    ->visible(fn (callable $get) => $get('has_workshop')),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Media')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Foto Utama')
                                    ->image()
                                    ->disk('public')
                                    ->directory('creative_economies')
                                    ->maxSize(1024)
                                    ->imageEditor(),
                            ]),

                        Forms\Components\Section::make('Pengaturan')
                            ->schema([
                                Forms\Components\Toggle::make('has_direct_selling')
                                    ->label('Menjual Langsung ke Konsumen')
                                    ->default(true),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Fitur di Halaman Utama')
                                    ->default(false),

                                Forms\Components\Toggle::make('is_verified')
                                    ->label('Terverifikasi')
                                    ->default(false)
                                    ->helperText('Tandai sebagai usaha yang sudah diverifikasi'),

                                Forms\Components\Toggle::make('accepts_credit_card')
                                    ->label('Menerima Kartu Kredit')
                                    ->default(false),

                                Forms\Components\Toggle::make('provides_training')
                                    ->label('Menyediakan Pelatihan')
                                    ->default(false),

                                Forms\Components\Toggle::make('shipping_available')
                                    ->label('Tersedia Pengiriman')
                                    ->default(false),

                                Forms\Components\Toggle::make('status')
                                    ->label('Status Aktif')
                                    ->default(true)
                                    ->helperText('Nonaktifkan jika usaha tidak beroperasi'),
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
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Jumlah Produk')
                    ->counts('products')
                    ->sortable(),

                Tables\Columns\IconColumn::make('has_workshop')
                    ->label('Workshop')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('is_verified')
                    ->label('Status')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Terverifikasi' : 'Belum Terverifikasi')
                    ->colors([
                        'success' => fn (bool $state): bool => $state,
                        'warning' => fn (bool $state): bool => !$state,
                    ]),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name'),

                Tables\Filters\TernaryFilter::make('has_workshop')
                    ->label('Menyediakan Workshop'),

                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Terverifikasi'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Unggulan'),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Aktif'),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAsFeatured')
                        ->label('Tandai Sebagai Unggulan')
                        ->icon('heroicon-o-star')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('markAsNotFeatured')
                        ->label('Hapus Dari Unggulan')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => false]))
                        ->color('danger')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('verifyBusinesses')
                        ->label('Verifikasi Usaha')
                        ->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->update(['is_verified' => true]))
                        ->color('success')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Components\Section::make('Informasi Utama')
    //                 ->schema([
    //                     Components\Group::make([
    //                         Components\TextEntry::make('name')
    //                             ->label('Nama UMKM/Usaha')
    //                             ->size(Components\TextEntry\TextEntrySize::Large),

    //                         Components\TextEntry::make('category.name')
    //                             ->label('Kategori'),

    //                         Components\TextEntry::make('short_description')
    //                             ->label('Deskripsi Singkat'),
    //                     ])->columns(2),

    //                     Components\ImageEntry::make('featured_image')
    //                         ->label('Foto Utama')
    //                         ->disk('public')
    //                         ->height(200)
    //                         ->columnSpanFull(),

    //                     Components\TextEntry::make('description')
    //                         ->label('Deskripsi Lengkap')
    //                         ->html()
    //                         ->columnSpanFull(),
    //                 ])
    //                 ->columns(2),

    //             Components\Group::make([
    //                 Components\Section::make('Lokasi & Kontak')
    //                     ->schema([
    //                         Components\TextEntry::make('address')
    //                             ->label('Alamat'),

    //                         Components\TextEntry::make('district.name')
    //                             ->label('Kecamatan'),

    //                         Components\Grid::make(2)
    //                             ->schema([
    //                                 Components\TextEntry::make('latitude')
    //                                     ->label('Latitude'),

    //                                 Components\TextEntry::make('longitude')
    //                                     ->label('Longitude'),
    //                             ]),

    //                         Components\Grid::make(2)
    //                             ->schema([
    //                                 Components\TextEntry::make('phone_number')
    //                                     ->label('Nomor Telepon')
    //                                     ->icon('heroicon-o-phone')
    //                                     ->url(fn (CreativeEconomy $record): ?string => $record->phone_number ? "tel:{$record->phone_number}" : null)
    //                                     ->openUrlInNewTab(),

    //                                 Components\TextEntry::make('email')
    //                                     ->label('Email')
    //                                     ->icon('heroicon-o-envelope')
    //                                     ->url(fn (CreativeEconomy $record): ?string => $record->email ? "mailto:{$record->email}" : null)
    //                                     ->openUrlInNewTab(),
    //                             ]),

    //                         Components\Grid::make(2)
    //                             ->schema([
    //                                 Components\TextEntry::make('website')
    //                                     ->label('Website')
    //                                     ->icon('heroicon-o-globe-alt')
    //                                     ->url(fn (CreativeEconomy $record): ?string => $record->website ? "https://{$record->website}" : null)
    //                                     ->openUrlInNewTab(),

    //                                 Components\TextEntry::make('social_media')
    //                                     ->label('Media Sosial')
    //                                     ->icon('heroicon-o-hashtag')
    //                                     ->url(fn (CreativeEconomy $record): ?string => $record->social_media ? "https://instagram.com/{$record->social_media}" : null)
    //                                     ->openUrlInNewTab(),
    //                             ]),
    //                     ]),

    //                 Components\Section::make('Detail Usaha')
    //                     ->schema([
    //                         Components\Grid::make(3)
    //                             ->schema([
    //                                 Components\TextEntry::make('business_hours')
    //                                     ->label('Jam Operasional')
    //                                     ->icon('heroicon-o-clock'),

    //                                 Components\TextEntry::make('owner_name')
    //                                     ->label('Nama Pemilik')
    //                                     ->icon('heroicon-o-user'),

    //                                 Components\TextEntry::make('establishment_year')
    //                                     ->label('Tahun Berdiri')
    //                                     ->icon('heroicon-o-calendar'),
    //                             ]),

    //                         Components\Grid::make(2)
    //                             ->schema([
    //                                 Components\TextEntry::make('employees_count')
    //                                     ->label('Jumlah Pekerja')
    //                                     ->icon('heroicon-o-users'),

    //                                 Components\TextEntry::make('priceRange')
    //                                     ->label('Kisaran Harga')
    //                                     ->formatStateUsing(fn (CreativeEconomy $record) =>
    //                                         'Rp ' . number_format($record->price_range_start, 0, ',', '.') .
    //                                         ' - Rp ' . number_format($record->price_range_end, 0, ',', '.'))
    //                                     ->icon('heroicon-o-currency-dollar'),
    //                             ]),

    //                         Components\TextEntry::make('products_description')
    //                             ->label('Deskripsi Produk')
    //                             ->columnSpanFull(),
    //                     ]),
    //             ])->columnSpan(['lg' => 2]),

    //             Components\Group::make([
    //                 Components\Section::make('Workshop')
    //                     ->schema([
    //                         Components\IconEntry::make('has_workshop')
    //                             ->label('Menyediakan Workshop')
    //                             ->boolean(),

    //                         Components\TextEntry::make('workshop_information')
    //                             ->label('Informasi Workshop')
    //                             ->visible(fn (CreativeEconomy $record) => $record->has_workshop)
    //                             ->columnSpanFull(),
    //                     ]),

    //                 Components\Section::make('Status & Fitur')
    //                     ->schema([
    //                         Components\Grid::make(2)
    //                             ->schema([
    //                                 Components\IconEntry::make('has_direct_selling')
    //                                     ->label('Menjual Langsung')
    //                                     ->boolean(),

    //                                 Components\IconEntry::make('is_verified')
    //                                     ->label('Terverifikasi')
    //                                     ->boolean(),
    //                             ]),

    //                         Components\Grid::make(2)
    //                             ->schema([
    //                                 Components\IconEntry::make('is_featured')
    //                                     ->label('Unggulan')
    //                                     ->boolean(),

    //                                 Components\IconEntry::make('status')
    //                                     ->label('Aktif')
    //                                     ->boolean(),
    //                             ]),

    //                         Components\Grid::make(2)
    //                             ->schema([
    //                                 Components\IconEntry::make('accepts_credit_card')
    //                                     ->label('Kartu Kredit')
    //                                     ->boolean(),

    //                                 Components\IconEntry::make('shipping_available')
    //                                     ->label('Pengiriman')
    //                                     ->boolean(),
    //                             ]),

    //                         Components\IconEntry::make('provides_training')
    //                             ->label('Menyediakan Pelatihan')
    //                             ->boolean(),
    //                     ]),

    //                 Components\Section::make('Peta Lokasi')
    //                     ->schema([
    //                         Components\ViewEntry::make('map')
    //                             ->view('filament.infolists.components.map-viewer')
    //                             ->state(fn ($record) => [
    //                                 'latitude' => $record->latitude,
    //                                 'longitude' => $record->longitude,
    //                                 'name' => $record->name,
    //                                 'address' => $record->address,
    //                             ])
    //                             ->columnSpanFull(),
    //                     ]),
    //             ])->columnSpan(['lg' => 1]),
    //         ])
    //         ->columns(3);
    // }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\GalleriesRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCreativeEconomies::route('/'),
            'create' => Pages\CreateCreativeEconomy::route('/create'),
            // 'view' => Pages\ViewCreativeEconomy::route('/{record}'),
            'edit' => Pages\EditCreativeEconomy::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'short_description', 'owner_name', 'address', 'products_description', 'district.name'];
    }

    // public static function getGlobalSearchResultTitle(Model $record): string
    // {
    //     return $record->name;
    // }

    // public static function getGlobalSearchResultDetails(Model $record): array
    // {
    //     return [
    //         'Kategori' => $record->category->name,
    //         'Kecamatan' => $record->district->name,
    //         'Pemilik' => $record->owner_name,
    //     ];
    // }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['category', 'district']);
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Kategori' => $record->category->name ?? 'Tidak Terkategori',
            'Kecamatan' => $record->district->name ?? 'Tidak Diketahui'
        ];
    }

    // public static function getGloballySearchableAttributes(): array
    // {
    //     return ['name', 'owner_name', 'products_description', 'address'];
    // }
}
