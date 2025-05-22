<?php

namespace App\Filament\Resources\DestinationResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Accommodation;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class NearbyAccommodationsRelationManager extends RelationManager
{
    protected static string $relationship = 'nearbyAccommodations';
    protected static ?string $title = 'Akomodasi Terdekat';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('accommodation_id')
                ->label('Pilih Akomodasi')
                ->options(Accommodation::query()->pluck('name', 'id'))
                ->required()
                ->searchable(),

            Forms\Components\TextInput::make('distance')
                ->label('Jarak (km)')
                ->numeric()
                ->required()
                ->minValue(0)
                ->maxValue(10),

            Forms\Components\Toggle::make('is_recommended')
                ->label('Rekomendasi')
                ->default(false),

            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Akomodasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hotel' => 'primary',
                        'villa' => 'success',
                        'homestay' => 'warning',
                        'resort' => 'danger',
                        'guesthouse' => 'info',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('pivot.distance')
                    ->label('Jarak')
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . ' km')
                    ->sortable(),

                Tables\Columns\IconColumn::make('pivot.is_recommended')
                    ->label('Rekomendasi')
                    ->boolean(),

                Tables\Columns\TextColumn::make('pivot.notes')
                    ->label('Catatan')
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Akomodasi')
                    ->options([
                        'hotel' => 'Hotel',
                        'homestay' => 'Homestay',
                        'villa' => 'Villa',
                        'resort' => 'Resort',
                        'guesthouse' => 'Guest House'
                    ]),

                Tables\Filters\Filter::make('distance')
                    ->form([
                        Forms\Components\TextInput::make('max_distance')
                            ->label('Jarak Maksimal (km)')
                            ->numeric()
                            ->default(5),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['max_distance'],
                            fn ($query, $distance) => $query->where('distance', '<=', $distance)
                        );
                    }),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('distance')
                            ->label('Jarak (km)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(10),
                        Forms\Components\Toggle::make('is_recommended')
                            ->label('Rekomendasi')
                            ->default(false),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->maxLength(255),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\Action::make('view_map')
                    ->label('Lihat di Peta')
                    ->icon('heroicon-o-map')
                    ->url(fn ($record) => "https://www.google.com/maps?q={$record->latitude},{$record->longitude}")
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
