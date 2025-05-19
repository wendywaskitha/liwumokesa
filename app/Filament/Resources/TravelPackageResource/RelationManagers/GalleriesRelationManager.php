<?php

namespace App\Filament\Resources\TravelPackageResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class GalleriesRelationManager extends RelationManager
{
    protected static string $relationship = 'galleries';

    protected static ?string $title = 'Galeri Foto';
    
    protected static ?string $recordTitleAttribute = 'caption';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file_path')
                    ->label('Gambar')
                    ->image()
                    ->required()
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('travel-packages/gallery')
                    ->columnSpanFull()
                    ->imageEditor(),
                    
                Forms\Components\TextInput::make('caption')
                    ->label('Keterangan')
                    ->maxLength(255),
                    
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'destination' => 'Destinasi',
                        'accommodation' => 'Akomodasi',
                        'transportation' => 'Transportasi',
                        'food' => 'Kuliner',
                        'activity' => 'Aktivitas',
                        'highlight' => 'Highlight',
                    ])
                    ->helperText('Kategori gambar membantu pengorganisasian galeri'),
                    
                Forms\Components\Toggle::make('is_featured')
                    ->label('Tampilkan sebagai unggulan')
                    ->default(false)
                    ->helperText('Gambar ini akan ditampilkan di galeri utama dan thumbnail'),
                    
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->helperText('Semakin kecil angka, semakin di depan posisinya'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->square(),
                    
                Tables\Columns\TextColumn::make('caption')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'destination' => 'Destinasi',
                        'accommodation' => 'Akomodasi',
                        'transportation' => 'Transportasi',
                        'food' => 'Kuliner',
                        'activity' => 'Aktivitas',
                        'highlight' => 'Highlight',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'destination' => 'success',
                        'accommodation' => 'info',
                        'transportation' => 'warning',
                        'food' => 'danger',
                        'activity' => 'primary',
                        'highlight' => 'secondary',
                        default => 'gray',
                    }),
                    
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Unggulan'),
                    
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Hanya Gambar Unggulan'),
                    
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'destination' => 'Destinasi',
                        'accommodation' => 'Akomodasi',
                        'transportation' => 'Transportasi',
                        'food' => 'Kuliner',
                        'activity' => 'Aktivitas',
                        'highlight' => 'Highlight',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Foto'),
                    
                Tables\Actions\Action::make('uploadMultiple')
                    ->label('Upload Multiple')
                    ->icon('heroicon-o-photo')
                    ->action(function (array $data, RelationManager $livewire) {
                        $category = $data['category'] ?? null;
                        foreach ($data['images'] as $image) {
                            $livewire->getOwnerRecord()->galleries()->create([
                                'file_path' => $image,
                                'caption' => $data['caption'] ?? 'Foto ' . $livewire->getOwnerRecord()->name,
                                'category' => $category,
                                'is_featured' => false,
                                'order' => 0,
                            ]);
                        }
                    })
                    ->form([
                        Forms\Components\FileUpload::make('images')
                            ->label('Gambar')
                            ->multiple()
                            ->maxFiles(10)
                            ->disk('public')
                            ->directory('travel-packages/gallery')
                            ->required()
                            ->image(),
                            
                        Forms\Components\TextInput::make('caption')
                            ->label('Keterangan (Opsional)')
                            ->maxLength(255),
                            
                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'destination' => 'Destinasi',
                                'accommodation' => 'Akomodasi',
                                'transportation' => 'Transportasi',
                                'food' => 'Kuliner',
                                'activity' => 'Aktivitas',
                                'highlight' => 'Highlight',
                            ]),
                    ]),
                    
                Tables\Actions\Action::make('setupItineraryGallery')
                    ->label('Setup Galeri Itinerary')
                    ->icon('heroicon-o-calendar')
                    ->color('secondary')
                    ->action(function (array $data, RelationManager $livewire) {
                        // Asumsi paket perjalanan memiliki relasi itinerary
                        $travelPackage = $livewire->getOwnerRecord();
                        $days = $travelPackage->duration;
                        
                        // Buat galeri per hari itinerary
                        for ($day = 1; $day <= $days; $day++) {
                            // Upload gambar untuk setiap hari
                            if (isset($data['day'.$day.'_images']) && !empty($data['day'.$day.'_images'])) {
                                foreach ($data['day'.$day.'_images'] as $image) {
                                    $travelPackage->galleries()->create([
                                        'file_path' => $image,
                                        'caption' => 'Hari '.$day.': '.$data['day'.$day.'_caption'],
                                        'category' => 'itinerary_day_'.$day,
                                        'is_featured' => false,
                                        'order' => $day * 10, // urutan berdasarkan hari
                                    ]);
                                }
                            }
                        }
                    })
                    ->form(function (RelationManager $livewire) {
                        $travelPackage = $livewire->getOwnerRecord();
                        $days = $travelPackage->duration;
                        
                        $formFields = [];
                        
                        // Buat field upload untuk setiap hari
                        for ($day = 1; $day <= $days; $day++) {
                            $formFields[] = Forms\Components\Section::make('Hari ' . $day)
                                ->schema([
                                    Forms\Components\TextInput::make('day'.$day.'_caption')
                                        ->label('Judul Aktivitas Hari '.$day)
                                        ->required(),
                                        
                                    Forms\Components\FileUpload::make('day'.$day.'_images')
                                        ->label('Foto Hari '.$day)
                                        ->multiple()
                                        ->maxFiles(5)
                                        ->disk('public')
                                        ->directory('travel-packages/gallery/day'.$day)
                                        ->image(),
                                ]);
                        }
                        
                        return $formFields;
                    })
                    ->modalWidth('xl')
                    ->visible(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->duration > 0),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                    
                Tables\Actions\Action::make('setAsCover')
                    ->label('Jadikan Cover Utama')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record, RelationManager $livewire) {
                        $travelPackage = $livewire->getOwnerRecord();
                        $travelPackage->update([
                            'featured_image' => $record->file_path
                        ]);
                        $livewire->notify('success', 'Gambar berhasil dijadikan cover utama paket perjalanan');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('setFeatured')
                        ->label('Set Sebagai Unggulan')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => true]))
                        ->icon('heroicon-o-star'),
                        
                    Tables\Actions\BulkAction::make('unsetFeatured')
                        ->label('Hapus dari Unggulan')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => false]))
                        ->icon('heroicon-o-x-mark'),
                        
                    Tables\Actions\BulkAction::make('setCategoryDestination')
                        ->label('Set Kategori: Destinasi')
                        ->action(fn (Collection $records) => $records->each->update(['category' => 'destination']))
                        ->color('success'),
                        
                    Tables\Actions\BulkAction::make('setCategoryAccommodation')
                        ->label('Set Kategori: Akomodasi')
                        ->action(fn (Collection $records) => $records->each->update(['category' => 'accommodation']))
                        ->color('info'),
                        
                    Tables\Actions\BulkAction::make('setCategoryTransportation')
                        ->label('Set Kategori: Transportasi')
                        ->action(fn (Collection $records) => $records->each->update(['category' => 'transportation']))
                        ->color('warning'),
                        
                    Tables\Actions\BulkAction::make('setCategoryFood')
                        ->label('Set Kategori: Kuliner')
                        ->action(fn (Collection $records) => $records->each->update(['category' => 'food']))
                        ->color('danger'),
                        
                    Tables\Actions\BulkAction::make('setCategoryActivity')
                        ->label('Set Kategori: Aktivitas')
                        ->action(fn (Collection $records) => $records->each->update(['category' => 'activity']))
                        ->color('primary'),
                        
                    Tables\Actions\BulkAction::make('setCategoryHighlight')
                        ->label('Set Kategori: Highlight')
                        ->action(fn (Collection $records) => $records->each->update(['category' => 'highlight']))
                        ->color('secondary'),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }
}
