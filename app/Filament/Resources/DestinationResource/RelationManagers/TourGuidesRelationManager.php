<?php

namespace App\Filament\Resources\DestinationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\TourGuide;

class TourGuidesRelationManager extends RelationManager
{
    protected static string $relationship = 'tourGuides';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Pemandu Wisata';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pemandu')
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
                            ->maxValue(50),

                        Forms\Components\FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->imageEditor()
                            ->directory('tour-guides')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('languages')
                            ->label('Bahasa yang Dikuasai')
                            ->suggestions([
                                'Indonesia', 'English', 'Muna', 'Buton', 'Mandarin',
                                'Japanese', 'Korean', 'French', 'German', 'Spanish'
                            ])
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('pivot.specialization')
                            ->label('Spesialisasi')
                            ->helperText('Keahlian khusus pemandu untuk destinasi ini')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('pivot.price')
                            ->label('Tarif')
                            ->helperText('Tarif untuk memandu di destinasi ini')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\Toggle::make('is_available')
                            ->label('Tersedia untuk Booking')
                            ->default(true),

                        Forms\Components\Textarea::make('pivot.notes')
                            ->label('Catatan')
                            ->helperText('Catatan terkait pemandu di destinasi ini')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => $record ?
                        "https://ui-avatars.com/api/?background=4338CA&color=fff&name={$record->name}" : ''),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
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
                                // Fallback to string
                            }
                        }

                        return (string) $record->languages;
                    }),

                Tables\Columns\TextColumn::make('experience_years')
                    ->label('Pengalaman')
                    ->suffix(' tahun')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('pivot.specialization')
                    ->label('Spesialisasi')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('pivot.price')
                    ->label('Tarif')
                    ->money('IDR')
                    ->alignRight()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 1))
                    ->suffix('/5.0')
                    ->color('warning')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('experience')
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

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Status Ketersediaan')
                    ->placeholder('Semua Status')
                    ->trueLabel('Tersedia')
                    ->falseLabel('Tidak Tersedia'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Pemandu Wisata'),

                Tables\Actions\AttachAction::make()
                    ->label('Tambahkan Pemandu Yang Ada')
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('specialization')
                            ->label('Spesialisasi')
                            ->helperText('Keahlian khusus pemandu untuk destinasi ini')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->label('Tarif')
                            ->helperText('Tarif untuk memandu di destinasi ini')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->helperText('Catatan terkait pemandu di destinasi ini')
                            ->rows(3),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Informasi Pemandu'),

                Tables\Actions\Action::make('toggleAvailability')
                    ->label(fn ($record) => $record->is_available ? 'Set Tidak Tersedia' : 'Set Tersedia')
                    ->icon(fn ($record) => $record->is_available ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->is_available ? 'danger' : 'success')
                    ->action(function (TourGuide $record) {
                        $record->update(['is_available' => !$record->is_available]);
                    })
                    ->requiresConfirmation(),

                Tables\Actions\DetachAction::make()
                    ->label('Lepaskan dari Destinasi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Lepaskan dari Destinasi'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Pemandu Wisata'),

                Tables\Actions\AttachAction::make()
                    ->label('Tambahkan Pemandu Yang Ada')
                    ->modalHeading('Pilih Pemandu Wisata'),
            ]);
    }
}
