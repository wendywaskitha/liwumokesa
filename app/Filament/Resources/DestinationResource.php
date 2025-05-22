<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Destination;
use App\Models\Category;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Filament\Resources\DestinationResource\Pages;
use App\Filament\Resources\DestinationResource\RelationManagers;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Wisata';

    protected static ?string $navigationLabel = 'Destinasi Wisata';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        // Cek apakah tabel categories dan kolom category_id ada
        $hasCategoryRelation = Schema::hasTable('categories') &&
                              Schema::hasColumn('destinations', 'category_id');

        return $form
            ->schema([
                Forms\Components\Tabs::make('Destination')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Dasar')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Destinasi')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) =>
                                        $operation === 'create' ? $set('slug', \Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug URL')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled(fn (string $operation) => $operation === 'edit'),

                                // Komponen Select untuk kategori yang lebih aman
                                $hasCategoryRelation ? Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship(
                                        name: 'category',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: function (Builder $query) {
                                            // Cek terlebih dahulu apakah kolom 'type' ada pada tabel categories
                                            if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'type')) {
                                                return $query->where('type', 'destination');
                                            }
                                            return $query;
                                        }
                                    )
                                    ->preload()
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Kategori')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Hidden::make('type')
                                            ->default('destination'),
                                        Forms\Components\Hidden::make('slug')
                                            ->default(fn ($state, $get) => \Str::slug($get('name'))),
                                    ]) : null,

                                Forms\Components\Select::make('district_id')
                                    ->label('Kecamatan')
                                    ->relationship('district', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required(),

                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Gambar Utama')
                                    ->image()
                                    ->directory('destinations')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->imageEditor()
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->columnSpanFull()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('destinations'),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Tampilkan di Halaman Utama')
                                    ->default(false),

                                Forms\Components\Toggle::make('status')
                                    ->label('Status Aktif')
                                    ->default(true)
                                    ->helperText('Destinasi akan ditampilkan jika status aktif'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Lokasi & Fasilitas')
                            ->schema([
                                Forms\Components\TextInput::make('location')
                                    ->label('Alamat Lengkap')
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('latitude')
                                            ->label('Latitude')
                                            ->required()
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(-90)
                                            ->maxValue(90),

                                        Forms\Components\TextInput::make('longitude')
                                            ->label('Longitude')
                                            ->required()
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(-180)
                                            ->maxValue(180),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Fasilitas Destinasi')
                                    ->description('Daftar fasilitas yang tersedia di destinasi ini')
                                    ->schema([
                                        // Menggunakan KeyValue yang lebih sederhana
                                        Forms\Components\KeyValue::make('facilities')
                                            ->label('Fasilitas')
                                            ->keyLabel('Nama Fasilitas')
                                            ->valueLabel('Keterangan')
                                            ->columnSpanFull()
                                            ->addActionLabel('Tambah Fasilitas')
                                            // Menggunakan getState untuk mendapatkan dan memodifikasi state
                                            ->afterStateHydrated(function ($component, $state) {
                                                // Jika string, coba decode untuk mendapatkan data asli
                                                if (is_string($state)) {
                                                    try {
                                                        $decoded = json_decode($state, true);

                                                        // Jika format awalnya adalah array objek [{name: '', description: ''}]
                                                        if (is_array($decoded) && isset($decoded[0]) && isset($decoded[0]['name'])) {
                                                            $result = [];
                                                            foreach ($decoded as $item) {
                                                                if (isset($item['name'])) {
                                                                    $result[$item['name']] = $item['description'] ?? '';
                                                                }
                                                            }
                                                            $component->state($result);
                                                        }
                                                    } catch (\Exception $e) {
                                                        // Fallback ke array kosong jika ada error
                                                        $component->state([]);
                                                    }
                                                }
                                            })
                                            ->dehydrateStateUsing(function ($state) {
                                                if (empty($state)) {
                                                    return json_encode([]);
                                                }

                                                $formatted = [];
                                                foreach ($state as $name => $description) {
                                                    $formatted[] = [
                                                        'name' => $name,
                                                        'description' => $description,
                                                        'type' => 'basic',
                                                        'is_available' => true
                                                    ];
                                                }

                                                return json_encode($formatted);
                                            }),

                                        Forms\Components\Placeholder::make('facilities_note')
                                            ->label('Catatan')
                                            ->content('Tambahkan fasilitas-fasilitas yang tersedia di destinasi ini. Nama fasilitas akan digunakan sebagai identifier.')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Informasi Operasional')
                            ->schema([
                                Forms\Components\TextInput::make('visiting_hours')
                                    ->label('Jam Operasional')
                                    ->placeholder('contoh: 08:00 - 17:00, Setiap Hari')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('entrance_fee')
                                    ->label('Harga Tiket')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0 untuk gratis'),

                                Forms\Components\TextInput::make('website')
                                    ->label('Website')
                                    ->url()
                                    ->placeholder('https://example.com')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('contact')
                                    ->label('Kontak')
                                    ->tel()
                                    ->placeholder('Nomor telepon atau email kontak')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('best_time_to_visit')
                                    ->label('Waktu Terbaik untuk Berkunjung')
                                    ->placeholder('contoh: Pagi hari, saat musim kemarau, dll')
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('tips')
                                    ->label('Tips Berkunjung')
                                    ->placeholder('Tips-tips bermanfaat untuk pengunjung')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Cek apakah tabel categories dan kolom category_id ada
        $hasCategoryRelation = Schema::hasTable('categories') &&
                              Schema::hasColumn('destinations', 'category_id');

        $columns = [
            Tables\Columns\ImageColumn::make('featured_image')
                ->label('Gambar')
                ->disk('public')
                ->square(),

            Tables\Columns\TextColumn::make('name')
                ->label('Nama Destinasi')
                ->searchable()
                ->sortable(),
        ];

        // Tambahkan kolom kategori hanya jika relasi ada
        if ($hasCategoryRelation) {
            $columns[] = Tables\Columns\TextColumn::make('category.name')
                ->label('Kategori')
                ->sortable()
                ->searchable();
        }

        // Tambahkan kolom-kolom lainnya
        $columns = array_merge($columns, [
            Tables\Columns\TextColumn::make('district.name')
                ->label('Kecamatan')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('entrance_fee')
                ->label('Harga Tiket')
                ->money('IDR')
                ->sortable(),

            Tables\Columns\IconColumn::make('is_featured')
                ->label('Unggulan')
                ->boolean()
                ->sortable(),

            Tables\Columns\IconColumn::make('status')
                ->label('Status Aktif')
                ->boolean()
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->dateTime('d M Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ]);

        $filters = [];

        // Tambahkan filter kategori hanya jika relasi ada
        if ($hasCategoryRelation) {
            $filters[] = Tables\Filters\SelectFilter::make('category_id')
                ->label('Kategori')
                ->relationship(
                    name: 'category',
                    titleAttribute: 'name',
                    modifyQueryUsing: function (Builder $query) {
                        // Cek terlebih dahulu apakah kolom 'type' ada pada tabel categories
                        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'type')) {
                            return $query->where('type', 'destination');
                        }
                        return $query;
                    }
                );
        }

        // Tambahkan filter-filter lainnya
        $filters = array_merge($filters, [
            Tables\Filters\SelectFilter::make('district_id')
                ->label('Kecamatan')
                ->relationship('district', 'name'),

            Tables\Filters\TernaryFilter::make('is_featured')
                ->label('Unggulan'),

            Tables\Filters\TernaryFilter::make('status')
                ->label('Status Aktif'),
        ]);

        return $table
            ->columns($columns)
            ->filters($filters)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleFeatured')
                        ->label('Toggle Unggulan')
                        ->icon('heroicon-o-star')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                $record->update(['is_featured' => !$record->is_featured]);
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('toggleStatus')
                        ->label('Toggle Status')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                $record->update(['status' => !$record->status]);
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GalleriesRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
            RelationManagers\TourGuidesRelationManager::class,
            RelationManagers\AmenitiesRelationManager::class,
            RelationManagers\NearbyAccommodationsRelationManager::class,
            RelationManagers\TransportationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestination::route('/create'),
            'view' => Pages\ViewDestination::route('/{record}'),
            'edit' => Pages\EditDestination::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        $query = parent::getGlobalSearchEloquentQuery();

        // Cek apakah relasi category ada sebelum melakukan with()
        if (Schema::hasColumn('destinations', 'category_id')) {
            $query->with(['category', 'district']);
        } else {
            $query->with('district');
        }

        return $query;
    }

    public static function getGloballySearchableAttributes(): array
    {
        $attributes = ['name', 'description', 'location', 'district.name'];

        // Tambahkan category.name jika relasi ada
        if (Schema::hasColumn('destinations', 'category_id')) {
            $attributes[] = 'category.name';
        }

        return $attributes;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [
            'Kecamatan' => $record->district->name,
        ];

        // Tambahkan informasi kategori jika relasi ada
        if (Schema::hasColumn('destinations', 'category_id') && $record->category) {
            $details['Kategori'] = $record->category->name;
        }

        return $details;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', true)->count();
    }
}
