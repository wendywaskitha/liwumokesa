<?php

namespace App\Filament\Resources\TravelPackageResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TourGuide;

class TourGuideRelationManager extends RelationManager
{
    protected static string $relationship = 'tourGuide';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Pemandu Wisata';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Pemandu')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),

                Forms\Components\TextInput::make('experience_years')
                    ->label('Pengalaman (tahun)')
                    ->disabled(),

                Forms\Components\TextInput::make('phone')
                    ->label('Telepon')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->description(function ($record) {
                        if (!$record || !$record->languages) return '';

                        if (is_array($record->languages)) {
                            return implode(', ', $record->languages);
                        }

                        if (is_string($record->languages)) {
                            try {
                                $decoded = json_decode($record->languages, true);
                                if (is_array($decoded)) {
                                    return implode(', ', $decoded);
                                }
                            } catch (\Exception $e) {
                                // Fallback to original
                            }
                        }

                        return (string) $record->languages;
                    }),

                Tables\Columns\TextColumn::make('experience_years')
                    ->label('Pengalaman')
                    ->suffix(' tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon'),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),
            ])
            ->filters([])
            ->headerActions([
                // Action untuk memilih tour guide
                Tables\Actions\Action::make('selectTourGuide')
                    ->label('Pilih Pemandu Wisata')
                    ->form([
                        Forms\Components\Select::make('tour_guide_id')
                            ->label('Pemandu Wisata')
                            ->options(TourGuide::where('is_available', true)->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Update travel package dengan tour_guide_id baru
                        $this->getOwnerRecord()->update([
                            'tour_guide_id' => $data['tour_guide_id'],
                        ]);

                        // Redirect ke halaman yang sama untuk refresh
                        return redirect(request()->header('Referer'));
                    }),
            ])
            ->actions([
                // Action untuk lihat detail
                Tables\Actions\ViewAction::make(),

                // Action untuk hapus tour guide
                Tables\Actions\Action::make('removeTourGuide')
                    ->label('Hapus Pemandu')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () {
                        // Set tour_guide_id ke null
                        $this->getOwnerRecord()->update([
                            'tour_guide_id' => null,
                        ]);

                        // Redirect untuk refresh
                        return redirect(request()->header('Referer'));
                    }),
            ])
            ->emptyStateHeading('Belum Ada Pemandu Wisata')
            ->emptyStateDescription('Pilih pemandu wisata untuk paket perjalanan ini')
            ->emptyStateActions([
                Tables\Actions\Action::make('selectTourGuide')
                    ->label('Pilih Pemandu Wisata')
                    ->form([
                        Forms\Components\Select::make('tour_guide_id')
                            ->label('Pemandu Wisata')
                            ->options(TourGuide::where('is_available', true)->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $this->getOwnerRecord()->update([
                            'tour_guide_id' => $data['tour_guide_id'],
                        ]);

                        return redirect(request()->header('Referer'));
                    }),
            ]);
    }

    // Method untuk menampilkan hanya tour guide yang terkait dengan travel package
    protected function getTableQuery(): Builder
    {
        $tourGuideId = $this->getOwnerRecord()->tour_guide_id;

        if ($tourGuideId) {
            return TourGuide::query()->where('id', $tourGuideId);
        }

        // Jika tidak ada tour guide, return empty query
        return TourGuide::query()->where('id', 0);
    }
}
