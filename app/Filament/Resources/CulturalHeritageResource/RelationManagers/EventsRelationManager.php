<?php

namespace App\Filament\Resources\CulturalHeritageResource\RelationManagers;

use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $title = 'Acara Terkait';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Acara')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->maxLength(255)
                    ->helperText('Akan dihasilkan otomatis dari nama jika dikosongkan'),

                Forms\Components\RichEditor::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->default(now())
                    ->displayFormat('d M Y H:i'),

                Forms\Components\DateTimePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->default(now()->addHours(2))
                    ->displayFormat('d M Y H:i')
                    ->afterOrEqual('start_date'),

                Forms\Components\Select::make('district_id')
                    ->relationship('district', 'name')
                    ->label('Kecamatan')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('location')
                    ->label('Lokasi Spesifik')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('organizer')
                    ->label('Penyelenggara')
                    ->maxLength(255),

                Forms\Components\TextInput::make('contact_person')
                    ->label('Kontak Person')
                    ->maxLength(255),

                Forms\Components\TextInput::make('contact_phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->maxLength(20),

                Forms\Components\TextInput::make('ticket_price')
                    ->label('Harga Tiket')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),

                Forms\Components\Toggle::make('is_free')
                    ->label('Gratis')
                    ->default(true)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('ticket_price', 0);
                        }
                    }),

                Forms\Components\TextInput::make('capacity')
                    ->label('Kapasitas')
                    ->numeric()
                    ->minValue(0),

                Forms\Components\Textarea::make('schedule_info')
                    ->label('Informasi Jadwal')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('facilities')
                    ->label('Fasilitas')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('featured_image')
                    ->label('Gambar Utama')
                    ->image()
                    ->directory('events')
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_recurring')
                    ->label('Acara Berulang')
                    ->default(false)
                    ->reactive(),

                Forms\Components\Select::make('recurring_type')
                    ->label('Tipe Pengulangan')
                    ->options([
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        'monthly' => 'Bulanan',
                        'yearly' => 'Tahunan',
                    ])
                    ->visible(fn (callable $get) => $get('is_recurring')),

                Forms\Components\Toggle::make('status')
                    ->label('Status Aktif')
                    ->default(true),

                Forms\Components\Toggle::make('is_featured')
                    ->label('Acara Unggulan')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->circular()
                    ->size(60),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Acara')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ticket_price')
                    ->label('Harga Tiket')
                    ->money('IDR')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => $state == 0 ? 'Gratis' : 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('upcoming')
                    ->label('Akan Datang')
                    ->query(fn (Builder $query): Builder => $query->where('start_date', '>=', now())),

                Tables\Filters\Filter::make('past')
                    ->label('Telah Selesai')
                    ->query(fn (Builder $query): Builder => $query->where('end_date', '<', now())),

                Tables\Filters\Filter::make('ongoing')
                    ->label('Sedang Berlangsung')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())),

                Tables\Filters\SelectFilter::make('is_featured')
                    ->label('Acara Unggulan')
                    ->options([
                        '1' => 'Ya',
                        '0' => 'Tidak',
                    ]),

                Tables\Filters\SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name'),
            ])
            ->headerActions([
                // Gunakan attach untuk relasi many-to-many
                Tables\Actions\AttachAction::make()
                    ->label('Tambahkan Acara yang Ada')
                    ->preloadRecordSelect(),

                // Buat acara baru
                Tables\Actions\CreateAction::make()
                    ->label('Buat Acara Baru'),

                // Kalender acara
                // Tables\Actions\Action::make('calendar_view')
                //     ->label('Lihat Kalender')
                //     ->icon('heroicon-o-calendar')
                //     ->color('success')
                //     ->url(fn (RelationManager $livewire) =>
                //         route('filament.admin.resources.events.calendar', [
                //             'cultural_heritage_id' => $livewire->ownerRecord->id
                //         ])
                //     )
                //     ->openUrlInNewTab(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // Gunakan detach untuk relasi many-to-many
                Tables\Actions\DetachAction::make()
                    ->label('Lepaskan'),

                Tables\Actions\Action::make('duplicate')
                    ->label('Duplikat')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record, RelationManager $livewire) {
                        $duplicate = $record->replicate();
                        $duplicate->name = $duplicate->name . ' (copy)';
                        $duplicate->slug = null; // Will be auto-generated
                        $duplicate->start_date = now()->addDays(1);
                        $duplicate->end_date = now()->addDays(1)->addHours(2);
                        $duplicate->save();

                        // Attach ke cultural heritage
                        $livewire->ownerRecord->events()->attach($duplicate->id);
                    }),

                Tables\Actions\Action::make('toggle_featured')
                    ->label(fn ($record) => $record->is_featured ? 'Hapus Unggulan' : 'Jadikan Unggulan')
                    ->icon(fn ($record) => $record->is_featured ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->action(function ($record) {
                        $record->update(['is_featured' => !$record->is_featured]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Gunakan detach untuk bulk action pada relasi many-to-many
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Lepaskan yang Dipilih'),

                    Tables\Actions\BulkAction::make('set_featured')
                        ->label('Jadikan Unggulan')
                        ->icon('heroicon-o-star')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => true]))
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('unset_featured')
                        ->label('Hapus Unggulan')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => false]))
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('start_date', 'desc');
    }
}
