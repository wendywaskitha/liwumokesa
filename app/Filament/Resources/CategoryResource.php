<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'destination' => 'Destinasi',
                                'culinary' => 'Kuliner',
                                'creative_economy' => 'Ekonomi Kreatif',
                                'event' => 'Acara/Event',
                                'other' => 'Lainnya',
                            ])
                            ->required(),

                        Forms\Components\Select::make('parent_id')
                            ->label('Kategori Induk')
                            ->relationship('parent', 'name', function (Builder $query, $get) {
                                // Hindari memilih diri sendiri sebagai parent
                                $query->whereNull('parent_id');

                                if ($get('id')) {
                                    $query->where('id', '!=', $get('id'));
                                }

                                // Filter parent dengan tipe yang sama
                                if ($get('type')) {
                                    $query->where('type', $get('type'));
                                }
                            })
                            ->searchable()
                            ->placeholder('Tidak ada'),

                        Forms\Components\FileUpload::make('icon')
                            ->label('Ikon')
                            ->image()
                            ->directory('categories')
                            ->maxSize(1024),

                        Forms\Components\ColorPicker::make('color')
                            ->label('Warna')
                            ->rgb(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->label('Ikon')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'destination' => 'Destinasi',
                        'culinary' => 'Kuliner',
                        'creative_economy' => 'Ekonomi Kreatif',
                        'event' => 'Acara/Event',
                        'other' => 'Lainnya',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match($state) {
                        'destination' => 'success',
                        'culinary' => 'warning',
                        'creative_economy' => 'primary',
                        'event' => 'danger',
                        'other' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Kategori Induk')
                    ->sortable(),

                // Destination count column
                Tables\Columns\TextColumn::make('destinations_count')
                    ->label('Jumlah Destinasi')
                    ->getStateUsing(function ($record) {
                        if (!Schema::hasTable('destinations') || !Schema::hasColumn('destinations', 'category_id')) {
                            return 0;
                        }

                        return DB::table('destinations')
                            ->where('category_id', $record->id)
                            ->count();
                    })
                    ->alignCenter()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        if (!Schema::hasTable('destinations') || !Schema::hasColumn('destinations', 'category_id')) {
                            return $query;
                        }

                        return $query->withCount('destinations')
                            ->orderBy('destinations_count', $direction);
                    }),

                // Culinary count column
                Tables\Columns\TextColumn::make('culinaries_count')
                    ->label('Jumlah Kuliner')
                    ->getStateUsing(function ($record) {
                        if (!Schema::hasTable('culinaries') || !Schema::hasColumn('culinaries', 'category_id')) {
                            return 0;
                        }

                        return DB::table('culinaries')
                            ->where('category_id', $record->id)
                            ->count();
                    })
                    ->alignCenter()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        if (!Schema::hasTable('culinaries') || !Schema::hasColumn('culinaries', 'category_id')) {
                            return $query;
                        }

                        return $query->withCount('culinaries')
                            ->orderBy('culinaries_count', $direction);
                    }),

                // Creative Economy count column
                Tables\Columns\TextColumn::make('creative_economies_count')
                    ->label('Jumlah Ekonomi Kreatif')
                    ->getStateUsing(function ($record) {
                        if (!Schema::hasTable('creative_economies') || !Schema::hasColumn('creative_economies', 'category_id')) {
                            return 0;
                        }

                        return DB::table('creative_economies')
                            ->where('category_id', $record->id)
                            ->count();
                    })
                    ->alignCenter()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        if (!Schema::hasTable('creative_economies') || !Schema::hasColumn('creative_economies', 'category_id')) {
                            return $query;
                        }

                        return $query->withCount('creativeEconomies')
                            ->orderBy('creative_economies_count', $direction);
                    }),

                // Events count column
                Tables\Columns\TextColumn::make('events_count')
                    ->label('Jumlah Event')
                    ->getStateUsing(function ($record) {
                        if (!Schema::hasTable('events') || !Schema::hasColumn('events', 'category_id')) {
                            return 0;
                        }

                        return DB::table('events')
                            ->where('category_id', $record->id)
                            ->count();
                    })
                    ->alignCenter()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        if (!Schema::hasTable('events') || !Schema::hasColumn('events', 'category_id')) {
                            return $query;
                        }

                        return $query->withCount('events')
                            ->orderBy('events_count', $direction);
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'destination' => 'Destinasi',
                        'culinary' => 'Kuliner',
                        'creative_economy' => 'Ekonomi Kreatif',
                        'event' => 'Acara/Event',
                        'other' => 'Lainnya',
                    ]),

                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Kategori Induk')
                    ->relationship('parent', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            // Tambahkan RelationManager sesuai kebutuhan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
