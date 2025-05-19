<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\TourGuide;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\TourGuideResource\Pages;

class TourGuideResource extends Resource
{
    protected static ?string $model = TourGuide::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Layanan Wisata';
    protected static ?string $navigationLabel = 'Pemandu Wisata';
    protected static ?int $navigationSort = 20;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Profil Pemandu')
                            ->description('Informasi dasar pemandu wisata')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Pemandu')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('phone')
                                    ->label('No. Telepon')
                                    ->tel()
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('experience_years')
                                    ->label('Pengalaman (tahun)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(50)
                                    ->default(0),

                                Forms\Components\Textarea::make('description')
                                    ->label('Biografi & Keahlian')
                                    ->rows(6)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Kemampuan Bahasa')
                            ->description('Bahasa yang dikuasai pemandu')
                            ->icon('heroicon-o-language')
                            ->schema([
                                Forms\Components\TagsInput::make('languages')
                                    ->label('Bahasa')
                                    ->placeholder('Tambahkan bahasa')
                                    ->suggestions([
                                        'Indonesia', 'English', 'Mandarin', 'Japanese',
                                        'Korean', 'French', 'German', 'Spanish',
                                        'Dutch', 'Arabic', 'Russian', 'Muna', 'Buton'
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Foto')
                            ->schema([
                                Forms\Components\FileUpload::make('photo')
                                    ->label('')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '3:4',
                                        '1:1',
                                    ])
                                    ->directory('tour-guides')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Rating & Status')
                            ->schema([
                                Forms\Components\Placeholder::make('rating_stars')
                                    ->label('Rating')
                                    ->content(function ($record) {
                                        if (!$record) return '⭐⭐⭐⭐⭐';
                                        $stars = str_repeat('⭐', min(5, round($record->rating)));
                                        return $stars ?: '⭐⭐⭐⭐⭐';
                                    }),

                                Forms\Components\TextInput::make('rating')
                                    ->label('Rating Saat Ini')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(5)
                                    ->step(0.1)
                                    ->suffix('/ 5.0')
                                    ->default(4.5),

                                Forms\Components\Toggle::make('is_available')
                                    ->label('Tersedia untuk Booking')
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-s-x-mark')
                                    ->default(true),

                                Forms\Components\Toggle::make('status')
                                    ->label('Status Aktif')
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-s-x-mark')
                                    ->default(true),
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
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => $record ?
                        "https://ui-avatars.com/api/?background=4338CA&color=fff&name={$record->name}" : '')
                    ->width(40)
                    ->height(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->description(function ($record) {
                        // Jika record tidak ada, kembalikan string kosong
                        if (!$record) return '';

                        // Ambil data bahasa
                        $languages = $record->languages;

                        // Jika null, kembalikan string kosong
                        if (is_null($languages)) return '';

                        // Jika sudah berbentuk array
                        if (is_array($languages)) {
                            return implode(', ', $languages);
                        }

                        // Jika berbentuk JSON string
                        if (is_string($languages) && Str::startsWith($languages, '[')) {
                            try {
                                $decoded = json_decode($languages, true);
                                if (is_array($decoded)) {
                                    return implode(', ', $decoded);
                                }
                            } catch (\Exception $e) {
                                // Jika error saat decode, kembalikan string aslinya
                            }
                        }

                        // Default, kembalikan string apa adanya
                        return (string) $languages;
                    }),

                Tables\Columns\TextColumn::make('experience_years')
                    ->label('Pengalaman')
                    ->suffix(' tahun')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 1))
                    ->suffix('/5.0')
                    ->color('warning')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ?: '-')
                    ->icon('heroicon-o-phone')
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ?: '-')
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('experience')
                    ->label('Pengalaman')
                    ->options([
                        '0-2' => 'Pemula (0-2 tahun)',
                        '3-5' => 'Menengah (3-5 tahun)',
                        '6-10' => 'Berpengalaman (6-10 tahun)',
                        '10+' => 'Senior (10+ tahun)'
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['value'])) return $query;

                        return match ($data['value']) {
                            '0-2' => $query->whereBetween('experience_years', [0, 2]),
                            '3-5' => $query->whereBetween('experience_years', [3, 5]),
                            '6-10' => $query->whereBetween('experience_years', [6, 10]),
                            '10+' => $query->where('experience_years', '>', 10),
                            default => $query
                        };
                    }),

                SelectFilter::make('languages')
                    ->label('Bahasa')
                    ->options([
                        'Indonesia' => 'Indonesia',
                        'English' => 'Inggris',
                        'Muna' => 'Muna',
                        'Mandarin' => 'Mandarin',
                        'Japanese' => 'Jepang'
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['value'])) return $query;

                        return $query->whereJsonContains('languages', $data['value']);
                    }),

                TernaryFilter::make('is_available')
                    ->label('Ketersediaan')
                    ->placeholder('Semua Guide')
                    ->trueLabel('Hanya yang Tersedia')
                    ->falseLabel('Hanya yang Tidak Tersedia')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_available', true),
                        false: fn (Builder $query) => $query->where('is_available', false),
                        blank: fn (Builder $query) => $query
                    ),

                TernaryFilter::make('status')
                    ->label('Status')
                    ->placeholder('Semua Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif')
                    ->queries(
                        true: fn (Builder $query) => $query->where('status', true),
                        false: fn (Builder $query) => $query->where('status', false),
                        blank: fn (Builder $query) => $query
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),

                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('toggleAvailability')
                        ->label(fn ($record) => $record->is_available ? 'Set Tidak Tersedia' : 'Set Tersedia')
                        ->icon(fn ($record) => $record->is_available ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn ($record) => $record->is_available ? 'danger' : 'success')
                        ->action(function (TourGuide $record) {
                            $record->update(['is_available' => !$record->is_available]);
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('setAvailable')
                        ->label('Set Tersedia')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_available' => true]))
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('setUnavailable')
                        ->label('Set Tidak Tersedia')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_available' => false]))
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('name')
            ->defaultGroup('experience_years', 'desc')
            ->striped()
            ->filtersLayout(FiltersLayout::Dropdown);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTourGuides::route('/'),
            'create' => Pages\CreateTourGuide::route('/create'),
            'view' => Pages\ViewTourGuide::route('/{record}'),
            'edit' => Pages\EditTourGuide::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        // Fungsi untuk memformat daftar bahasa
        $formatLanguages = function ($languages) {
            if (is_null($languages)) return '-';

            if (is_array($languages)) {
                return implode(', ', $languages);
            }

            if (is_string($languages) && Str::startsWith($languages, '[')) {
                try {
                    $decoded = json_decode($languages, true);
                    if (is_array($decoded)) {
                        return implode(', ', $decoded);
                    }
                } catch (\Exception $e) {
                    // Fallback to original string
                }
            }

            return (string) $languages;
        };

        return [
            'Pengalaman' => $record->experience_years . ' tahun',
            'Bahasa' => $formatLanguages($record->languages),
            'Status' => $record->is_available ? 'Tersedia' : 'Tidak Tersedia',
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', true)
            ->where('is_available', true)
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
