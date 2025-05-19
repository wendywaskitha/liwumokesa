<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Destination;
use Illuminate\Support\Str;
use App\Models\TravelPackage;
use Illuminate\Support\Number;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Components\Group;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Collection;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TravelPackageResource\Pages;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use App\Filament\Resources\TravelPackageResource\RelationManagers;

class TravelPackageResource extends Resource
{

    // Array statis untuk tipe paket
    public static $packageTypes = [
        'private' => 'Private Tour',
        'open' => 'Open Trip',
        'group' => 'Group Tour',
        'family' => 'Family Package',
        'custom' => 'Custom Tour',
    ];
    protected static ?string $model = TravelPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Paket Wisata';

    protected static ?string $navigationGroup = 'Layanan Wisata';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Dasar')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Paket')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $state, callable $set) =>
                                        $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(TravelPackage::class, 'slug', fn ($record) => $record),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->columnSpanFull(),

                                // Forms\Components\Select::make('tour_guide_id')
                                //     ->label('Pemandu Wisata')
                                //     ->relationship('tourGuide', 'name')
                                //     ->searchable()
                                //     ->preload()
                                //     ->createOptionForm([
                                //         Forms\Components\TextInput::make('name')
                                //             ->label('Nama')
                                //             ->required(),
                                //         Forms\Components\TextInput::make('phone')
                                //             ->label('No. Telepon')
                                //             ->tel(),
                                //         Forms\Components\TextInput::make('email')
                                //             ->label('Email')
                                //             ->email(),
                                //         Forms\Components\FileUpload::make('photo')
                                //             ->label('Foto')
                                //             ->image()
                                //             ->directory('tour-guides')
                                //     ])
                                //     ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                //         return $action
                                //             ->modalWidth('md')
                                //             ->modalHeading('Buat Pemandu Wisata Baru');
                                //     }),

                                Forms\Components\TextInput::make('duration')
                                    ->label('Durasi (hari)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required(),

                                Forms\Components\Select::make('type')
                                    ->label('Tipe Paket')
                                    ->options(self::$packageTypes)
                                    ->default('private')
                                    ->required()
                                    ->reactive(),

                                // Fields tanggal untuk Open Trip
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'open')
                                    ->required(fn (Forms\Get $get) => $get('type') === 'open'),

                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Tanggal Selesai')
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'open')
                                    ->required(fn (Forms\Get $get) => $get('type') === 'open')
                                    ->after('start_date'),

                                Forms\Components\Select::make('difficulty')
                                    ->label('Tingkat Kesulitan')
                                    ->options([
                                        'easy' => 'Mudah - Cocok untuk semua usia',
                                        'moderate' => 'Sedang - Membutuhkan kebugaran dasar',
                                        'challenging' => 'Menantang - Perlu kondisi fisik baik',
                                        'difficult' => 'Sulit - Untuk yang berpengalaman',
                                    ])
                                    ->default('easy'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Harga dan Ketersediaan')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga (Rp)')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->inputMode('decimal'),

                                Forms\Components\TextInput::make('min_participants')
                                    ->label('Minimal Peserta')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1),

                                Forms\Components\TextInput::make('max_participants')
                                    ->label('Maksimal Peserta')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(10)
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                        if ((int)$get('min_participants') > (int)$state) {
                                            $set('min_participants', $state);
                                        }
                                    }),

                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'open')
                                    ->required(fn (Forms\Get $get) => $get('type') === 'open'),

                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Tanggal Berakhir')
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'open')
                                    ->required(fn (Forms\Get $get) => $get('type') === 'open')
                                    ->afterOrEqual('start_date'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Jadwal dan Destinasi')
                            ->schema([
                                Forms\Components\KeyValue::make('itinerary')
                                    ->label('Itinerary')
                                    ->keyLabel('Hari ke-')
                                    ->valueLabel('Judul Kegiatan')
                                    ->addActionLabel('Tambah Hari')
                                    ->afterStateHydrated(function ($component, $state) {
                                        if (empty($state)) {
                                            return;
                                        }

                                        if (is_string($state)) {
                                            try {
                                                $decoded = json_decode($state, true);

                                                // Jika format awalnya adalah array objek
                                                if (is_array($decoded) && isset($decoded[0]) && isset($decoded[0]['day'])) {
                                                    $result = [];
                                                    foreach ($decoded as $item) {
                                                        if (isset($item['day']) && isset($item['title'])) {
                                                            $result['Hari ' . $item['day']] = $item['title'];
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
                                        $counter = 1;
                                        foreach ($state as $day => $title) {
                                            // Extract day number if possible
                                            $dayNumber = intval(preg_replace('/[^0-9]/', '', $day));
                                            if ($dayNumber === 0) $dayNumber = $counter;

                                            $formatted[] = [
                                                'day' => $dayNumber,
                                                'title' => $title,
                                                'description' => '',
                                                'destinations' => []
                                            ];
                                            $counter++;
                                        }

                                        return json_encode($formatted);
                                    })
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('destinations')
                                    ->label('Destinasi yang Dikunjungi')
                                    ->multiple()
                                    ->relationship('destinations', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull(),

                                Forms\Components\Placeholder::make('itinerary_note')
                                    ->label('Catatan')
                                    ->content('Masukkan jadwal rencana perjalanan dengan format "Hari X" dan Judul Kegiatannya. Anda bisa menentukan destinasi yang dikunjungi di bawahnya.')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Gambar dan Status')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Gambar Utama')
                                    ->image()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1280')
                                    ->imageResizeTargetHeight('720')
                                    ->directory('travel-packages')
                                    ->required(),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Paket Unggulan')
                                    ->helperText('Tampilkan di halaman beranda')
                                    ->default(false),

                                Forms\Components\Toggle::make('status')
                                    ->label('Status Aktif')
                                    ->helperText('Paket tersedia untuk dipesan')
                                    ->default(true),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Tanggal Publikasi')
                                    ->default(now()),
                            ]),

                        Forms\Components\Section::make('Fasilitas dan Layanan')
                            ->schema([
                                // Penanganan inclusions dengan KeyValue
                                Forms\Components\KeyValue::make('inclusions')
                                    ->label('Termasuk dalam Paket')
                                    ->keyLabel('Item')
                                    ->valueLabel('Keterangan')
                                    ->addActionLabel('Tambah Item')
                                    ->columnSpanFull()
                                    ->afterStateHydrated(function ($component, $state) {
                                        if (empty($state)) {
                                            return;
                                        }

                                        if (is_string($state)) {
                                            try {
                                                $decoded = json_decode($state, true);

                                                if (is_array($decoded)) {
                                                    // Jika array sederhana, konversi ke key-value
                                                    if (isset($decoded[0]) && !is_array($decoded[0])) {
                                                        $result = [];
                                                        foreach ($decoded as $index => $value) {
                                                            $result['Item ' . ($index + 1)] = $value;
                                                        }
                                                        $component->state($result);
                                                    }
                                                    // Jika array objek, konversi ke key-value
                                                    else if (isset($decoded[0]) && is_array($decoded[0]) && isset($decoded[0]['item'])) {
                                                        $result = [];
                                                        foreach ($decoded as $item) {
                                                            $result[$item['item']] = $item['description'] ?? '';
                                                        }
                                                        $component->state($result);
                                                    }
                                                    // Jika sudah key-value, gunakan langsung
                                                    else {
                                                        $component->state($decoded);
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $component->state([]);
                                            }
                                        }
                                    }),

                                // Penanganan exclusions dengan KeyValue
                                Forms\Components\KeyValue::make('exclusions')
                                    ->label('Tidak Termasuk dalam Paket')
                                    ->keyLabel('Item')
                                    ->valueLabel('Keterangan')
                                    ->addActionLabel('Tambah Item')
                                    ->columnSpanFull()
                                    ->afterStateHydrated(function ($component, $state) {
                                        if (empty($state)) {
                                            return;
                                        }

                                        if (is_string($state)) {
                                            try {
                                                $decoded = json_decode($state, true);

                                                if (is_array($decoded)) {
                                                    // Jika array sederhana, konversi ke key-value
                                                    if (isset($decoded[0]) && !is_array($decoded[0])) {
                                                        $result = [];
                                                        foreach ($decoded as $index => $value) {
                                                            $result['Item ' . ($index + 1)] = $value;
                                                        }
                                                        $component->state($result);
                                                    }
                                                    // Jika array objek, konversi ke key-value
                                                    else if (isset($decoded[0]) && is_array($decoded[0]) && isset($decoded[0]['item'])) {
                                                        $result = [];
                                                        foreach ($decoded as $item) {
                                                            $result[$item['item']] = $item['description'] ?? '';
                                                        }
                                                        $component->state($result);
                                                    }
                                                    // Jika sudah key-value, gunakan langsung
                                                    else {
                                                        $component->state($decoded);
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $component->state([]);
                                            }
                                        }
                                    }),

                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Tambahan')
                                    ->rows(3),
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
                    ->label('Gambar')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string =>
                        self::$packageTypes[$state] ?? $state ?? 'Tidak Ditentukan'
                    )
                    ->colors([
                        'primary' => 'private',
                        'success' => 'open',
                        'warning' => 'group',
                        'danger' => 'custom',
                        'info' => 'family',
                    ]),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Jadwal Mulai')
                    ->date()
                    ->visible(fn ($livewire) => $livewire->getTableFilterState()['type'] === 'open')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Durasi')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => "{$state} hari"),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                // Kolom tanggal mulai - tidak menggunakan koneksi ke filter yang bermasalah
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tourGuide.name')
                    ->label('Pemandu')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('itinerary')
                    ->label('Destinasi')
                    ->getStateUsing(function ($record) {
                        // Count destinations in itinerary
                        if (!$record->itinerary) return '0 destinasi';

                        $destinationCount = 0;
                        foreach ($record->itinerary as $day) {
                            if (isset($day['destinations']) && is_array($day['destinations'])) {
                                $destinationCount += count($day['destinations']);
                            }
                        }

                        return $destinationCount . ' destinasi';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publikasi')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('type')
                //     ->label('Tipe Paket')
                //     ->options(self::$packageTypes),

                Tables\Filters\SelectFilter::make('difficulty')
                    ->label('Tingkat Kesulitan')
                    ->options([
                        'easy' => 'Mudah',
                        'moderate' => 'Sedang',
                        'challenging' => 'Menantang',
                        'difficult' => 'Sulit',
                    ]),

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
                                fn (Builder $query, $price): Builder => $query->where('price', '>=', $price),
                            )
                            ->when(
                                $data['price_to'],
                                fn (Builder $query, $price): Builder => $query->where('price', '<=', $price),
                            );
                    }),

                Tables\Filters\SelectFilter::make('tour_guide_id')
                    ->label('Pemandu Wisata')
                    ->relationship('tourGuide', 'name'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Paket Unggulan'),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Aktif'),

                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_from')
                            ->label('Dipublikasi Sejak'),
                        Forms\Components\DatePicker::make('published_until')
                            ->label('Dipublikasi Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplikat')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (TravelPackage $record) {
                        $duplicate = $record->replicate();
                        $duplicate->name = $duplicate->name . ' (copy)';
                        $duplicate->slug = Str::slug($duplicate->name);
                        $duplicate->save();

                        // Copy relationships if needed
                        if ($record->destinations) {
                            $duplicate->destinations()->attach($record->destinations->pluck('id'));
                        }

                        return redirect()->route('filament.admin.resources.travel-packages.edit', ['record' => $duplicate->id]);
                    }),
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

                    Tables\Actions\BulkAction::make('activatePackages')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => $records->each->update(['status' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivatePackages')
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
                // Header dengan informasi utama
                Section::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Group::make([
                                    TextEntry::make('name')
                                        ->label('Nama Paket')
                                        ->size(TextEntrySize::Large)
                                        ->weight(FontWeight::Bold),

                                    TextEntry::make('type')
                                        ->label('Tipe Paket')
                                        ->badge()
                                        ->formatStateUsing(fn (?string $state): string =>
                                            match($state) {
                                                'private' => 'Private Tour',
                                                'open' => 'Open Trip',
                                                'group' => 'Group Tour',
                                                'family' => 'Family Package',
                                                'custom' => 'Custom Tour',
                                                default => $state ?? 'Tidak Ditentukan'
                                            }
                                        )
                                        ->color(fn (string $state): string => match($state) {
                                            'private' => 'primary',
                                            'open' => 'success',
                                            'group' => 'warning',
                                            'family' => 'info',
                                            'custom' => 'danger',
                                            default => 'gray',
                                        }),
                                ])->columnSpan(2),

                                Group::make([
                                    ImageEntry::make('featured_image')
                                        ->label('Foto Utama')
                                        ->height(120)
                                        ->alignEnd(),
                                ])->columnSpan(1),
                            ]),
                    ]),

                // Informasi dasar paket wisata
                Section::make('Detail Paket')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->html()
                            ->columnSpanFull(),
                        // Menggunakan Grid dengan 4 kolom untuk detail paket
                        Grid::make(4)
                            ->schema([
                                // Kolom 1: Durasi & Tipe Paket
                                Group::make([
                                    TextEntry::make('duration')
                                        ->label('Durasi Perjalanan')
                                        ->prefix('⏱️ ')
                                        ->suffix(' hari')
                                        ->color('primary')
                                        ->weight(FontWeight::Bold)
                                        ->size(TextEntrySize::Large),

                                    TextEntry::make('type')
                                        ->label('Jenis Paket')
                                        ->icon('heroicon-o-ticket')
                                        ->formatStateUsing(fn (string $state): string => match($state) {
                                            'private' => 'Private Tour',
                                            'open' => 'Open Trip',
                                            'group' => 'Group Tour',
                                            'family' => 'Family Package',
                                            'custom' => 'Custom Tour',
                                            default => $state,
                                        })
                                        ->badge()
                                        ->color(fn (string $state): string => match($state) {
                                            'private' => 'primary',
                                            'open' => 'success',
                                            'group' => 'warning',
                                            'family' => 'info',
                                            'custom' => 'danger',
                                            default => 'gray',
                                        }),
                                ])
                                ->columnSpan(1)
                                ->extraAttributes([
                                    'class' => 'p-4 bg-white rounded-xl shadow-sm border border-gray-200 space-y-3',
                                ]),

                                // Kolom 2: Tingkat Kesulitan & Partisipan
                                Group::make([
                                    TextEntry::make('difficulty')
                                        ->label('Tingkat Kesulitan')
                                        ->formatStateUsing(fn (string $state): string => match($state) {
                                            'easy' => '⭐ Mudah',
                                            'moderate' => '⭐⭐ Sedang',
                                            'challenging' => '⭐⭐⭐ Menantang',
                                            'difficult' => '⭐⭐⭐⭐ Sulit',
                                            default => $state,
                                        })
                                        ->color(fn (string $state): string => match($state) {
                                            'easy' => 'success',
                                            'moderate' => 'info',
                                            'challenging' => 'warning',
                                            'difficult' => 'danger',
                                            default => 'gray',
                                        }),

                                    TextEntry::make('max_participants')
                                        ->label('Kapasitas')
                                        ->icon('heroicon-o-user-group')
                                        ->iconColor('info')
                                        ->suffix(' peserta')
                                        ->extraAttributes(['class' => 'font-medium']),
                                ])
                                ->columnSpan(1)
                                ->extraAttributes([
                                    'class' => 'p-4 bg-white rounded-xl shadow-sm border border-gray-200 space-y-3',
                                ]),

                                // Kolom 3: Harga & Rating
                                Group::make([
                                    TextEntry::make('price')
                                        ->label('Harga Paket')
                                        ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                                        ->color('success')
                                        ->weight(FontWeight::Bold)
                                        ->size(TextEntrySize::Large),

                                    TextEntry::make('average_rating')
                                        ->label('Rating')
                                        ->formatStateUsing(function ($state, $record) {
                                            $rating = $record->average_rating ?? 0;
                                            $stars = str_repeat('⭐', min(5, (int)$rating));
                                            return $stars . ' ' . number_format($rating, 1) . '/5';
                                        })
                                        ->color('warning'),
                                ])
                                ->columnSpan(1)
                                ->extraAttributes([
                                    'class' => 'p-4 bg-white rounded-xl shadow-sm border border-gray-200 space-y-3',
                                ]),

                                // Kolom 4: Pemandu & Jadwal
                                Group::make([
                                    TextEntry::make('tourGuide.name')
                                        ->label('Pemandu')
                                        ->prefix('👤 ')
                                        ->placeholder('Belum ditentukan')
                                        ->extraAttributes(['class' => 'font-medium']),

                                    // Tanggal untuk Open Trip
                                    Group::make([
                                        TextEntry::make('start_date')
                                            ->label('Mulai')
                                            ->date('d M Y')
                                            ->icon('heroicon-o-calendar')
                                            ->color('primary'),

                                        TextEntry::make('end_date')
                                            ->label('Selesai')
                                            ->date('d M Y')
                                            ->icon('heroicon-o-flag')
                                            ->color('danger'),
                                    ])
                                    ->visible(fn ($record) => $record->type === 'open')
                                    ->extraAttributes(['class' => 'mt-2 space-y-1']),
                                ])
                                ->columnSpan(1)
                                ->extraAttributes([
                                    'class' => 'p-4 bg-white rounded-xl shadow-sm border border-gray-200 space-y-3',
                                ]),
                            ])
                            ->extraAttributes([
                                'class' => 'gap-4',
                            ]),

                    ]),

                // Itinerary
                Section::make('Itinerary')
                    ->schema([
                        TextEntry::make('itinerary')
                            ->label('Jadwal Perjalanan')
                            ->formatStateUsing(function ($state) {
                                // Jika null atau kosong, tampilkan placeholder
                                if (empty($state)) {
                                    return new HtmlString('<em>Belum ada itinerary</em>');
                                }

                                // Jika berbentuk string (JSON), decode ke array
                                if (is_string($state)) {
                                    try {
                                        $state = json_decode($state, true);

                                        if (!is_array($state)) {
                                            return new HtmlString('<em>Format itinerary tidak valid</em>');
                                        }
                                    } catch (\Exception $e) {
                                        return new HtmlString('<em>Error: ' . htmlspecialchars($e->getMessage()) . '</em>');
                                    }
                                }

                                // Buat HTML untuk menampilkan itinerary
                                $html = '<div class="space-y-4">';

                                foreach ($state as $item) {
                                    if (!is_array($item)) continue;

                                    $day = $item['day'] ?? '?';
                                    $title = $item['title'] ?? 'Tidak ada judul';
                                    $description = $item['description'] ?? '';

                                    $html .= '<div class="py-1 pl-4 border-l-4 border-primary-500">';
                                    $html .= '<h3 class="font-bold">Hari ' . htmlspecialchars($day) . '</h3>';
                                    $html .= '<h4 class="text-primary-500">' . htmlspecialchars($title) . '</h4>';

                                    if (!empty($description)) {
                                        $html .= '<p class="mt-1 text-gray-600">' . htmlspecialchars($description) . '</p>';
                                    }

                                    if (!empty($item['destinations']) && is_array($item['destinations'])) {
                                        $html .= '<div class="mt-2">';
                                        $html .= '<p class="text-sm font-medium">Destinasi:</p>';
                                        $html .= '<ul class="pl-2 text-sm list-disc list-inside">';

                                        foreach ($item['destinations'] as $destination) {
                                            if (is_array($destination) && isset($destination['name'])) {
                                                $html .= '<li>' . htmlspecialchars($destination['name']) . '</li>';
                                            } elseif (is_string($destination)) {
                                                $html .= '<li>' . htmlspecialchars($destination) . '</li>';
                                            }
                                        }

                                        $html .= '</ul>';
                                        $html .= '</div>';
                                    }

                                    $html .= '</div>';
                                }

                                $html .= '</div>';
                                return new HtmlString($html);
                            })
                            ->columnSpanFull(),

                        TextEntry::make('destinations.name')
                            ->label('Semua Destinasi')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->columnSpanFull(),
                    ]),

                Section::make('Fasilitas & Layanan')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        // Inclusions (Termasuk dalam paket)
                        // Inclusions (Termasuk dalam paket)
        TextEntry::make('inclusions')
            ->label('Termasuk dalam Paket')
            ->formatStateUsing(function ($state) {
                if (empty($state)) {
                    return new HtmlString(
                        '<div class="italic text-gray-500">Tidak ada data fasilitas yang termasuk</div>'
                    );
                }

                // Decode JSON jika dalam bentuk string
                $items = is_string($state) ? json_decode($state, true) : $state;

                if (!is_array($items)) {
                    return new HtmlString(
                        '<div class="italic text-gray-500">Format data tidak valid</div>'
                    );
                }

                $html = '<ul class="pl-5 space-y-1 list-disc">';
                foreach ($items as $key => $value) {
                    if (is_array($value)) {
                        $text = $value['item'] ?? '';
                        $description = $value['description'] ?? '';
                        $html .= sprintf(
                            '<li class="text-gray-700">%s%s</li>',
                            htmlspecialchars($text),
                            $description ? ' <span class="text-gray-500">('.htmlspecialchars($description).')</span>' : ''
                        );
                    } else {
                        $html .= sprintf(
                            '<li class="text-gray-700">%s</li>',
                            htmlspecialchars(is_string($key) && !is_numeric($key) ? "$key: $value" : $value)
                        );
                    }
                }
                $html .= '</ul>';

                return new HtmlString($html);
            })
            ->extraAttributes([
                'class' => 'bg-green-50 p-4 rounded-lg',
            ])
            ->columnSpan(1),

        // Exclusions (Tidak termasuk dalam paket)
        TextEntry::make('exclusions')
            ->label('Tidak Termasuk dalam Paket')
            ->formatStateUsing(function ($state) {
                if (empty($state)) {
                    return new HtmlString(
                        '<div class="italic text-gray-500">Tidak ada data fasilitas yang tidak termasuk</div>'
                    );
                }

                // Decode JSON jika dalam bentuk string
                $items = is_string($state) ? json_decode($state, true) : $state;

                if (!is_array($items)) {
                    return new HtmlString(
                        '<div class="italic text-gray-500">Format data tidak valid</div>'
                    );
                }

                $html = '<ul class="pl-5 space-y-1 list-disc">';
                foreach ($items as $key => $value) {
                    if (is_array($value)) {
                        $text = $value['item'] ?? '';
                        $description = $value['description'] ?? '';
                        $html .= sprintf(
                            '<li class="text-gray-700">%s%s</li>',
                            htmlspecialchars($text),
                            $description ? ' <span class="text-gray-500">('.htmlspecialchars($description).')</span>' : ''
                        );
                    } else {
                        $html .= sprintf(
                            '<li class="text-gray-700">%s</li>',
                            htmlspecialchars(is_string($key) && !is_numeric($key) ? "$key: $value" : $value)
                        );
                    }
                }
                $html .= '</ul>';

                return new HtmlString($html);
            })
            ->extraAttributes([
                'class' => 'bg-red-50 p-4 rounded-lg',
            ])
            ->columnSpan(1),

                        // Exclusions (Tidak termasuk dalam paket)
                        // TextEntry::make('exclusions')
                        //     ->label('Tidak Termasuk dalam Paket')
                        //     ->formatStateUsing(function ($state, $record) {
                        //         if (empty($state)) {
                        //             return new HtmlString(
                        //                 '<div class="italic text-gray-500">Tidak ada data fasilitas yang tidak termasuk</div>'
                        //             );
                        //         }

                        //         // Prepare the data - handle various formats
                        //         $items = [];

                        //         // If JSON string, attempt to decode
                        //         if (is_string($state) && (
                        //             str_starts_with(trim($state), '[') ||
                        //             str_starts_with(trim($state), '{')
                        //         )) {
                        //             try {
                        //                 $decodedState = json_decode($state, true);
                        //                 if (json_last_error() === JSON_ERROR_NONE) {
                        //                     $state = $decodedState;
                        //                 }
                        //             } catch (\Exception $e) {
                        //                 // If decode fails, continue with original string
                        //             }
                        //         }

                        //         // Build HTML based on data format
                        //         $html = '<div class="space-y-2">';

                        //         // Handle different data structures
                        //         if (is_array($state)) {
                        //             // Case 1: Simple array of strings
                        //             if (isset($state[0]) && is_string($state[0])) {
                        //                 $html .= '<ul class="pl-5 space-y-1 list-disc">';
                        //                 foreach ($state as $item) {
                        //                     $html .= '<li class="text-gray-700">'.htmlspecialchars($item).'</li>';
                        //                 }
                        //                 $html .= '</ul>';
                        //             }
                        //             // Case 2: Array of objects with item property
                        //             else if (isset($state[0]) && is_array($state[0]) && isset($state[0]['item'])) {
                        //                 $html .= '<ul class="pl-5 space-y-1 list-disc">';
                        //                 foreach ($state as $item) {
                        //                     $itemText = htmlspecialchars($item['item']);
                        //                     if (!empty($item['description'])) {
                        //                         $itemText .= ' <span class="text-gray-500">('.htmlspecialchars($item['description']).')</span>';
                        //                     }

                        //                     $html .= '<li class="text-gray-700">'.$itemText.'</li>';
                        //                 }
                        //                 $html .= '</ul>';
                        //             }
                        //             // Case 3: Key-value object
                        //             else {
                        //                 $html .= '<ul class="pl-5 space-y-1 list-disc">';
                        //                 foreach ($state as $key => $value) {
                        //                     if (is_string($key) && !is_numeric($key)) {
                        //                         $itemText = htmlspecialchars($key);
                        //                         if (!empty($value) && is_string($value)) {
                        //                             $itemText .= ' <span class="text-gray-500">('.htmlspecialchars($value).')</span>';
                        //                         }
                        //                         $html .= '<li class="text-gray-700">'.$itemText.'</li>';
                        //                     } else if (is_string($value)) {
                        //                         $html .= '<li class="text-gray-700">'.htmlspecialchars($value).'</li>';
                        //                     }
                        //                 }
                        //                 $html .= '</ul>';
                        //             }
                        //         }
                        //         // Handle string data
                        //         else if (is_string($state)) {
                        //             // Try to parse as line items
                        //             $lines = explode("\n", $state);
                        //             if (count($lines) > 1) {
                        //                 $html .= '<ul class="pl-5 space-y-1 list-disc">';
                        //                 foreach ($lines as $line) {
                        //                     $line = trim($line);
                        //                     if (!empty($line)) {
                        //                         // Remove leading dash or bullet if exists
                        //                         $line = ltrim($line, '-• ');
                        //                         $html .= '<li class="text-gray-700">'.htmlspecialchars($line).'</li>';
                        //                     }
                        //                 }
                        //                 $html .= '</ul>';
                        //             } else {
                        //                 $html .= '<div class="prose">'.htmlspecialchars($state).'</div>';
                        //             }
                        //         }

                        //         $html .= '</div>';
                        //         return new HtmlString($html);
                        //     })
                        //     ->extraAttributes([
                        //         'class' => 'bg-red-50 p-4 rounded-lg',
                        //     ])
                        //     ->columnSpan(1),

                        // Notes
                        TextEntry::make('notes')
                            ->label('Catatan Tambahan')
                            ->markdown()
                            ->visible(fn ($state) => !empty($state))
                            ->extraAttributes([
                                'class' => 'bg-gray-50 p-4 rounded-lg',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->extraAttributes([
                        'class' => 'mt-8',
                    ]),

                // Informasi tambahan
                Section::make('Informasi Tambahan')
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('published_at')
                                    ->label('Tanggal Publikasi')
                                    ->date(),

                                TextEntry::make('created_at')
                                    ->label('Dibuat pada')
                                    ->dateTime(),

                                TextEntry::make('is_featured')
                                    ->label('Status Unggulan')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak')
                                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),

                                TextEntry::make('status')
                                    ->label('Status Aktif')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Non-Aktif')
                                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TourGuideRelationManager::class,
            RelationManagers\BookingsRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
            RelationManagers\GalleriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTravelPackages::route('/'),
            'create' => Pages\CreateTravelPackage::route('/create'),
            'view' => Pages\ViewTravelPackage::route('/{record}'),
            'edit' => Pages\EditTravelPackage::route('/{record}/edit'),
        ];
    }

    // Helper untuk mendapatkan nama tipe paket
    public static function getPackageTypeName(?string $type): string
    {
        return self::$packageTypes[$type] ?? 'Tidak Ditentukan';
    }
}
