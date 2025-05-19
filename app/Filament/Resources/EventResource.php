<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use App\Models\District;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Colors\Color;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    protected static ?string $navigationLabel = 'Event & Festival';
    
    protected static ?string $navigationGroup = 'Wisata Budaya';
    
    protected static ?int $navigationSort = 60;
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Event')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, Set $set) => 
                                $set('slug', Str::slug($state))),
                            
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                            
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),
                            
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Tanggal & Jam Mulai')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y H:i')
                            ->default(now()),
                            
                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Tanggal & Jam Selesai')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y H:i')
                            ->default(now()->addHours(3))
                            ->afterOrEqual('start_date'),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1280')
                            ->imageResizeTargetHeight('720')
                            ->directory('events')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Lokasi')
                    ->schema([
                        Forms\Components\Select::make('district_id')
                            ->label('Kecamatan')
                            ->options(District::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Set $set) => $set('latitude', null) && $set('longitude', null)),
                            
                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi Detail')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.0000001),
                                    
                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.0000001),
                            ])
                            ->columns(2)
                    ]),
                    
                Forms\Components\Section::make('Detail Event')
                    ->schema([
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
                            ->default(0)
                            ->disabled(fn (Get $get) => $get('is_free')),
                            
                        Forms\Components\Toggle::make('is_free')
                            ->label('Gratis')
                            ->default(true)
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
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
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pengulangan & Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_recurring')
                            ->label('Acara Berulang')
                            ->helperText('Acara yang akan diadakan secara berkala')
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
                            ->visible(fn (Get $get) => $get('is_recurring'))
                            ->required(fn (Get $get) => $get('is_recurring')),
                            
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Acara Unggulan')
                            ->helperText('Tampilkan di bagian event unggulan')
                            ->default(false),
                            
                        Forms\Components\Toggle::make('status')
                            ->label('Status Aktif')
                            ->helperText('Nonaktifkan berarti tidak ditampilkan di aplikasi')
                            ->default(true),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Warisan Budaya Terkait')
                    ->schema([
                        Forms\Components\Select::make('cultural_heritages')
                            ->label('Warisan Budaya')
                            ->relationship('culturalHeritages', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('type')
                                    ->label('Jenis')
                                    ->options([
                                        'tangible' => 'Warisan Budaya Berwujud',
                                        'intangible' => 'Warisan Budaya Tak Berwujud',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('location')
                                    ->label('Lokasi')
                                    ->maxLength(255),
                                Forms\Components\Select::make('district_id')
                                    ->label('Kecamatan')
                                    ->relationship('district', 'name')
                                    ->required()
                                    ->preload(),
                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('')
                    ->circular(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('ticket_price')
                    ->label('Tiket')
                    ->money('IDR')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => $state == 0 ? 'Gratis' : 'Rp ' . number_format($state, 0, ',', '.')),
                    
                Tables\Columns\IconColumn::make('is_recurring')
                    ->label('Berulang')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                    
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
                        
                Tables\Filters\SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Acara Unggulan'),
                    
                Tables\Filters\TernaryFilter::make('is_recurring')
                    ->label('Acara Berulang'),
                    
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplikat')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (Event $record) {
                        $duplicate = $record->replicate();
                        $duplicate->name = $duplicate->name . ' (copy)';
                        $duplicate->slug = Str::slug($duplicate->name);
                        $duplicate->start_date = now()->addDays(1)->setHour(
                            $record->start_date->hour
                        )->setMinute(
                            $record->start_date->minute
                        );
                        $duplicate->end_date = now()->addDays(1)->setHour(
                            $record->end_date->hour
                        )->setMinute(
                            $record->end_date->minute
                        );
                        $duplicate->save();
                        
                        // Copy cultural heritage relationships
                        $duplicate->culturalHeritages()->attach(
                            $record->culturalHeritages->pluck('id')->toArray()
                        );
                        
                        return redirect()->route('filament.admin.resources.events.edit', ['record' => $duplicate->id]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setFeatured')
                        ->label('Jadikan Unggulan')
                        ->icon('heroicon-o-star')
                        ->action(fn (Builder $query) => $query->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->color('warning')
                        ->requiresConfirmation(),
                        
                    Tables\Actions\BulkAction::make('unsetFeatured')
                        ->label('Hapus dari Unggulan')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn (Builder $query) => $query->update(['is_featured' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->color('gray')
                        ->requiresConfirmation(),
                        
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Builder $query) => $query->update(['status' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->color('success')
                        ->requiresConfirmation(),
                        
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Builder $query) => $query->update(['status' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->color('danger')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('start_date');
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Event')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nama Event')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                            
                        Infolists\Components\Group::make([
                            Infolists\Components\TextEntry::make('start_date')
                                ->label('Mulai')
                                ->dateTime('d M Y, H:i'),
                                
                            Infolists\Components\TextEntry::make('end_date')
                                ->label('Selesai')
                                ->dateTime('d M Y, H:i'),
                        ])->columns(2),
                        
                        Infolists\Components\ImageEntry::make('featured_image')
                            ->label('Gambar Utama')
                            ->height(400)
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->featured_image),
                            
                        Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi')
                            ->markdown()
                            ->columnSpanFull(),
                    ]),
                    
                Infolists\Components\Grid::make(3)
                    ->schema([
                        Infolists\Components\Section::make('Lokasi')
                            ->schema([
                                Infolists\Components\TextEntry::make('district.name')
                                    ->label('Kecamatan'),
                                    
                                Infolists\Components\TextEntry::make('location')
                                    ->label('Lokasi Detail'),
                                    
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('latitude')
                                        ->label('Latitude'),
                                        
                                    Infolists\Components\TextEntry::make('longitude')
                                        ->label('Longitude'),
                                ])->columns(2),
                            ])
                            ->columnSpan(1),
                            
                        Infolists\Components\Section::make('Informasi Tiket')
                            ->schema([
                                Infolists\Components\TextEntry::make('ticket_price')
                                    ->label('Harga Tiket')
                                    ->formatStateUsing(fn (string $state): string => 
                                        $state == 0 ? 'Gratis' : 'Rp ' . number_format($state, 0, ',', '.')
                                    )
                                    ->icon(fn ($record) => $record->is_free ? 'heroicon-o-ticket' : 'heroicon-o-currency-dollar')
                                    ->iconColor(fn ($record) => $record->is_free ? 'success' : 'primary'),
                                    
                                Infolists\Components\TextEntry::make('capacity')
                                    ->label('Kapasitas')
                                    ->formatStateUsing(fn ($state) => $state ? $state . ' orang' : 'Tidak ada batasan')
                                    ->icon('heroicon-o-user-group'),
                                    
                                Infolists\Components\TextEntry::make('facilities')
                                    ->label('Fasilitas')
                                    ->visible(fn ($record) => !empty($record->facilities)),
                            ])
                            ->columnSpan(1),
                            
                        Infolists\Components\Section::make('Penyelenggara')
                            ->schema([
                                Infolists\Components\TextEntry::make('organizer')
                                    ->label('Nama Penyelenggara')
                                    ->default('Tidak disebutkan'),
                                    
                                Infolists\Components\TextEntry::make('contact_person')
                                    ->label('Kontak Person')
                                    ->default('Tidak disebutkan'),
                                    
                                Infolists\Components\TextEntry::make('contact_phone')
                                    ->label('Telepon')
                                    ->icon('heroicon-o-phone')
                                    ->default('Tidak disebutkan')
                                    ->url(fn ($record) => $record->contact_phone ? "tel:{$record->contact_phone}" : null)
                                    ->visible(fn ($record) => !empty($record->contact_phone)),
                            ])
                            ->columnSpan(1),
                    ]),
                    
                Infolists\Components\Section::make('Peta Lokasi')
                    ->schema([
                        Infolists\Components\ViewEntry::make('map')
                            ->label(false)
                            ->view('filament.infolists.components.map-viewer')
                            ->state(fn ($record) => [
                                'latitude' => $record->latitude,
                                'longitude' => $record->longitude,
                                'name' => $record->name,
                                'address' => $record->location,
                            ])
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->latitude && $record->longitude),
                    ])
                    ->collapsible(),
                
                Infolists\Components\Section::make('Warisan Budaya Terkait')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('culturalHeritages')
                            ->hiddenLabel()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nama')
                                    ->weight('bold'),
                                    
                                Infolists\Components\TextEntry::make('type')
                                    ->label('Jenis')
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'tangible' => 'Warisan Budaya Berwujud',
                                        'intangible' => 'Warisan Budaya Tak Berwujud',
                                        default => $state,
                                    })
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'tangible' => 'success',
                                        'intangible' => 'info',
                                        default => 'gray',
                                    }),
                                    
                                Infolists\Components\TextEntry::make('district.name')
                                    ->label('Kecamatan'),
                                    
                                Infolists\Components\TextEntry::make('location')
                                    ->label('Lokasi'),
                            ])
                            ->columns(4)
                            ->visible(fn ($record) => $record->culturalHeritages->count() > 0),
                            
                        Infolists\Components\TextEntry::make('no_cultural_heritages')
                            ->state('Tidak ada warisan budaya terkait dengan acara ini.')
                            ->visible(fn ($record) => $record->culturalHeritages->count() === 0),
                    ])
                    ->collapsible(),
                    
                Infolists\Components\Section::make('Status Acara')
                    ->schema([
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\IconEntry::make('is_featured')
                                    ->label('Acara Unggulan')
                                    ->boolean(),
                                    
                                Infolists\Components\IconEntry::make('is_recurring')
                                    ->label('Acara Berulang')
                                    ->boolean(),
                                    
                                Infolists\Components\TextEntry::make('recurring_type')
                                    ->label('Jenis Pengulangan')
                                    ->visible(fn ($record) => $record->is_recurring)
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'daily' => 'Harian',
                                        'weekly' => 'Mingguan',
                                        'monthly' => 'Bulanan',
                                        'yearly' => 'Tahunan',
                                        default => $state,
                                    }),
                                    
                                Infolists\Components\IconEntry::make('status')
                                    ->label('Status Aktif')
                                    ->boolean(),
                            ]),
                            
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d F Y, H:i'),
                            
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d F Y, H:i'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GalleriesRelationManager::class,
            RelationManagers\RegistrationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'calendar' => Pages\EventCalendar::route('/calendar'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }
    
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Tanggal' => $record->start_date->format('d M Y'),
            'Lokasi' => $record->location
        ];
    }
    
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description', 'location', 'organizer'];
    }
}
