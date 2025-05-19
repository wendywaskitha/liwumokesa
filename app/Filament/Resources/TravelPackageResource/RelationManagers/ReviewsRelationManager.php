<?php

namespace App\Filament\Resources\TravelPackageResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Review;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Ulasan Paket Wisata';
    
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Wisatawan')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(100),
                    
                Forms\Components\Textarea::make('content')
                    ->label('Isi Ulasan')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '⭐ - Sangat Buruk',
                        2 => '⭐⭐ - Buruk',
                        3 => '⭐⭐⭐ - Cukup Baik',
                        4 => '⭐⭐⭐⭐ - Baik',
                        5 => '⭐⭐⭐⭐⭐ - Sangat Baik',
                    ])
                    ->required(),
                    
                Forms\Components\DatePicker::make('review_date')
                    ->label('Tanggal Ulasan')
                    ->default(now())
                    ->required(),
                    
                Forms\Components\FileUpload::make('images')
                    ->label('Foto')
                    ->multiple()
                    ->image()
                    ->maxFiles(5)
                    ->directory('reviews')
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Moderasi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('pending')
                    ->required(),
                
                Forms\Components\Toggle::make('is_verified_purchase')
                    ->label('Pembelian Terverifikasi')
                    ->default(false),
                
                Forms\Components\Textarea::make('admin_notes')
                    ->label('Catatan Admin')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('user.profile_photo')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user?->name ?? 'User') . '&color=7F9CF5&background=EBF4FF'),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->sortable()
                    ->searchable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state): string => str_repeat('⭐', $state))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('review_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_verified_purchase')
                    ->label('Terverifikasi')
                    ->boolean()
                    ->tooltip('Pembelian Terverifikasi'),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Moderasi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_verified_purchase')
                    ->label('Pembelian Terverifikasi'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Ulasan'),
                    
                Tables\Actions\Action::make('refreshRating')
                    ->label('Segarkan Rating')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (RelationManager $livewire) {
                        // Logic to recalculate average rating
                        $travelPackage = $livewire->ownerRecord;
                        $approvedReviews = $travelPackage->reviews()->where('status', 'approved');
                        
                        $avgRating = $approvedReviews->avg('rating') ?? 0;
                        $reviewCount = $approvedReviews->count();
                        
                        // Update travel package with new rating data
                        $travelPackage->update([
                            'average_rating' => round($avgRating, 1),
                            'review_count' => $reviewCount
                        ]);
                        
                        $livewire->notify('success', 'Rating berhasil diperbarui');
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn (Review $record) => $record->update(['status' => 'approved']))
                        ->visible(fn (Review $record): bool => $record->status !== 'approved'),
                        
                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn (Review $record) => $record->update(['status' => 'rejected']))
                        ->visible(fn (Review $record): bool => $record->status !== 'rejected'),
                        
                    Tables\Actions\Action::make('verify')
                        ->label('Tandai Terverifikasi')
                        ->icon('heroicon-o-badge-check')
                        ->color('primary')
                        ->action(fn (Review $record) => $record->update(['is_verified_purchase' => true]))
                        ->visible(fn (Review $record): bool => !$record->is_verified_purchase),
                        
                    Tables\Actions\Action::make('reply')
                        ->label('Balas Ulasan')
                        ->icon('heroicon-o-chat-bubble-left')
                        ->form([
                            Forms\Components\Textarea::make('reply')
                                ->label('Balasan')
                                ->required(),
                        ])
                        ->action(function (array $data, Review $record) {
                            // Logic to save reply
                            $record->update([
                                'admin_reply' => $data['reply'],
                                'admin_reply_date' => now()
                            ]);
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approveMultiple')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each(fn (Review $record) => $record->update(['status' => 'approved'])))
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\BulkAction::make('rejectMultiple')
                        ->label('Tolak Terpilih')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn (Collection $records) => $records->each(fn (Review $record) => $record->update(['status' => 'rejected'])))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('review_date', 'desc');
    }
}
