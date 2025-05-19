<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Review;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReviewResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReviewResource\RelationManagers\ResponsesRelationManager;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Ulasan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Ulasan')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Pengguna'),

                        Forms\Components\Select::make('reviewable_type')
                            ->label('Tipe Item')
                            ->options([
                                'App\Models\Destination' => 'Destinasi',
                                'App\Models\Accommodation' => 'Akomodasi',
                                'App\Models\Transportation' => 'Transportasi',
                                'App\Models\Culinary' => 'Kuliner',
                                'App\Models\CreativeEconomy' => 'Ekonomi Kreatif',
                                'App\Models\TravelPackage' => 'Paket Wisata',
                            ])
                            ->required()
                            ->reactive(),

                        Forms\Components\Select::make('reviewable_id')
                            ->label('Item')
                            ->options(function (callable $get) {
                                $type = $get('reviewable_type');

                                if (!$type) {
                                    return [];
                                }

                                $model = new $type;

                                return $model::pluck('name', 'id')->toArray();
                            })
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('rating')
                            ->label('Rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required(),

                        Forms\Components\Textarea::make('comment')
                            ->label('Komentar')
                            ->nullable()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reviewable_type')
                    ->label('Tipe')
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'App\Models\Destination' => 'Destinasi',
                            'App\Models\Accommodation' => 'Akomodasi',
                            'App\Models\Transportation' => 'Transportasi',
                            'App\Models\Culinary' => 'Kuliner',
                            'App\Models\CreativeEconomy' => 'Ekonomi Kreatif',
                            'App\Models\TravelPackage' => 'Paket Wisata',
                            default => $state,
                        };
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('reviewable_id')
                    ->label('Item')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->reviewable_type) {
                            return $state;
                        }

                        try {
                            $model = $record->reviewable_type::find($state);
                            return $model ? $model->name : $state;
                        } catch (\Exception $e) {
                            return $state;
                        }
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->html()
                    ->sortable(),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Komentar')
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('reviewable_type')
                    ->label('Tipe')
                    ->options([
                        'App\Models\Destination' => 'Destinasi',
                        'App\Models\Accommodation' => 'Akomodasi',
                        'App\Models\Transportation' => 'Transportasi',
                        'App\Models\Culinary' => 'Kuliner',
                        'App\Models\CreativeEconomy' => 'Ekonomi Kreatif',
                        'App\Models\TravelPackage' => 'Paket Wisata',
                    ]),

                Tables\Filters\Filter::make('rating')
                    ->form([
                        Forms\Components\Select::make('rating')
                            ->options([
                                '5' => '5 Bintang',
                                '4' => '4 Bintang',
                                '3' => '3 Bintang',
                                '2' => '2 Bintang',
                                '1' => '1 Bintang',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['rating'],
                            fn (Builder $query, $rating): Builder => $query->where('rating', $rating)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Review $record) {
                            $record->update(['status' => 'approved']);
                        })
                        ->visible(fn (Review $record) => $record->status !== 'approved'),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (Review $record) {
                            $record->update(['status' => 'rejected']);
                        })
                        ->visible(fn (Review $record) => $record->status !== 'rejected'),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'approved']))
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'rejected']))
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ResponsesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'view' => Pages\ViewReview::route('/{record}'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Pengguna' => $record->user->name,
            'Rating' => str_repeat('★', $record->rating),
            'Status' => ucfirst($record->status),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['comment', 'user.name'];
    }
}
