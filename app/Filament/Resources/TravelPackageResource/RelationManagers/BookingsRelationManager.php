<?php

namespace App\Filament\Resources\TravelPackageResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $title = 'Pemesanan Paket Wisata';
    
    protected static ?string $recordTitleAttribute = 'booking_code';

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
                    
                Forms\Components\TextInput::make('booking_code')
                    ->label('Kode Booking')
                    ->default(fn () => 'BOOK-' . strtoupper(Str::random(8)))
                    ->disabled()
                    ->dehydrated(),
                    
                Forms\Components\DatePicker::make('booking_date')
                    ->label('Tanggal Pemesanan')
                    ->default(now())
                    ->required(),
                    
                Forms\Components\DatePicker::make('trip_start_date')
                    ->label('Tanggal Mulai Perjalanan')
                    ->required()
                    ->afterOrEqual('booking_date'),
                    
                Forms\Components\TextInput::make('number_of_people')
                    ->label('Jumlah Peserta')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->required(),
                    
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'confirmed' => 'Terkonfirmasi',
                        'cancelled' => 'Dibatalkan',
                        'completed' => 'Selesai',
                        'refunded' => 'Direfund',
                    ])
                    ->default('pending')
                    ->required(),
                    
                Forms\Components\TextInput::make('total_price')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                    
                Forms\Components\TextInput::make('discount_amount')
                    ->label('Jumlah Diskon')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                    
                Forms\Components\Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'credit_card' => 'Kartu Kredit',
                        'e_wallet' => 'E-Wallet',
                        'on_site' => 'Bayar di Tempat',
                    ])
                    ->required(),
                    
                Forms\Components\DatePicker::make('payment_date')
                    ->label('Tanggal Pembayaran')
                    ->afterOrEqual('booking_date'),
                    
                Forms\Components\Toggle::make('is_paid')
                    ->label('Sudah Dibayar')
                    ->default(false),
                    
                Forms\Components\Select::make('guide_id')
                    ->label('Pemandu Wisata')
                    ->relationship('guide', 'name')
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\Textarea::make('special_requests')
                    ->label('Permintaan Khusus')
                    ->rows(3)
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan Admin')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->copyable()
                    ->tooltip('Klik untuk menyalin'),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Wisatawan')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('trip_start_date')
                    ->label('Tanggal Trip')
                    ->date('d M Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('number_of_people')
                    ->label('Jumlah Peserta')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'confirmed' => 'Terkonfirmasi',
                        'cancelled' => 'Dibatalkan',
                        'completed' => 'Selesai',
                        'refunded' => 'Direfund',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'primary',
                        'refunded' => 'info',
                        default => 'gray',
                    }),
                    
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Dibayar')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('guide.name')
                    ->label('Pemandu')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Tgl Booking')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'confirmed' => 'Terkonfirmasi',
                        'cancelled' => 'Dibatalkan',
                        'completed' => 'Selesai',
                        'refunded' => 'Direfund',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_paid')
                    ->label('Sudah Dibayar'),
                    
                Tables\Filters\Filter::make('trip_start_date')
                    ->form([
                        Forms\Components\DatePicker::make('trip_from')
                            ->label('Trip Dari'),
                        Forms\Components\DatePicker::make('trip_until')
                            ->label('Trip Hingga'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['trip_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('trip_start_date', '>=', $date),
                            )
                            ->when(
                                $data['trip_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('trip_start_date', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Pemesanan')
                    ->mutateFormDataUsing(function (array $data, RelationManager $livewire): array {
                        // Calculate price based on travel package price
                        $travelPackage = $livewire->ownerRecord;
                        $totalPrice = $travelPackage->price * $data['number_of_people'];
                        $data['total_price'] = $totalPrice - ($data['discount_amount'] ?? 0);
                        
                        return $data;
                    })
                    ->after(function (RelationManager $livewire, $record) {
                        // Optional: Create booking items or any post-booking logic here
                    }),
                    
                Tables\Actions\Action::make('exportBookings')
                    ->label('Export Pemesanan')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (RelationManager $livewire) {
                        // Example export functionality
                        $travelPackage = $livewire->ownerRecord;
                        $bookings = $travelPackage->bookings;
                        
                        return response()->streamDownload(function () use ($bookings, $travelPackage) {
                            echo "Daftar Pemesanan untuk Paket Wisata: {$travelPackage->name}\n";
                            echo "Tanggal Export: " . now()->format('d/m/Y H:i') . "\n\n";
                            
                            echo "Kode,Wisatawan,Tanggal Trip,Jumlah Peserta,Total Harga,Status,Dibayar\n";
                            foreach ($bookings as $booking) {
                                echo "{$booking->booking_code},{$booking->user->name},{$booking->trip_start_date->format('d/m/Y')},{$booking->number_of_people},".
                                     "Rp ".number_format($booking->total_price, 0, ',', '.').",{$booking->status},".($booking->is_paid ? 'Ya' : 'Tidak')."\n";
                            }
                        }, "pemesanan-{$travelPackage->slug}.csv");
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('confirm')
                        ->label('Konfirmasi Pemesanan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Booking $record) => $record->update(['status' => 'confirmed', 'is_paid' => true, 'payment_date' => now()]))
                        ->visible(fn (Booking $record): bool => $record->status === 'pending'),
                        
                    Tables\Actions\Action::make('cancel')
                        ->label('Batalkan Pemesanan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Booking $record) => $record->update(['status' => 'cancelled']))
                        ->visible(fn (Booking $record): bool => in_array($record->status, ['pending', 'confirmed'])),
                        
                    Tables\Actions\Action::make('markComplete')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-flag')
                        ->color('success')
                        ->action(fn (Booking $record) => $record->update(['status' => 'completed']))
                        ->visible(fn (Booking $record): bool => $record->status === 'confirmed'),
                        
                    Tables\Actions\Action::make('assignGuide')
                        ->label('Assign Pemandu')
                        ->icon('heroicon-o-user-circle')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('guide_id')
                                ->label('Pemandu Wisata')
                                ->options(
                                    User::role('guide')->pluck('name', 'id')->toArray()
                                )
                                ->required(),
                        ])
                        ->action(function (array $data, Booking $record) {
                            $record->update(['guide_id' => $data['guide_id']]);
                        }),
                        
                    Tables\Actions\Action::make('sendVoucher')
                        ->label('Kirim Voucher')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('info')
                        ->action(function (Booking $record) {
                            // Logic to send voucher via email
                            // Mail::to($record->user->email)->send(new BookingVoucherMail($record));
                        })
                        ->visible(fn (Booking $record): bool => $record->status === 'confirmed' && $record->is_paid),
                        
                    Tables\Actions\Action::make('printVoucher')
                        ->label('Cetak Voucher')
                        ->icon('heroicon-o-printer')
                        ->color('gray')
                        ->url(fn (Booking $record): string => route('booking.voucher', $record))
                        ->openUrlInNewTab()
                        ->visible(fn (Booking $record): bool => $record->status === 'confirmed' && $record->is_paid),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('bulkConfirm')
                        ->label('Konfirmasi Semua')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each(fn (Booking $record) => $record->update([
                            'status' => 'confirmed',
                            'is_paid' => true,
                            'payment_date' => now()
                        ])))
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\BulkAction::make('bulkExport')
                        ->label('Export Terpilih')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            // Example export functionality for selected records
                            return response()->streamDownload(function () use ($records) {
                                echo "Daftar Pemesanan Terpilih\n";
                                echo "Tanggal Export: " . now()->format('d/m/Y H:i') . "\n\n";
                                
                                echo "Kode,Wisatawan,Tanggal Trip,Jumlah Peserta,Total Harga,Status,Dibayar\n";
                                foreach ($records as $booking) {
                                    echo "{$booking->booking_code},{$booking->user->name},{$booking->trip_start_date->format('d/m/Y')},{$booking->number_of_people},".
                                         "Rp ".number_format($booking->total_price, 0, ',', '.').",{$booking->status},".($booking->is_paid ? 'Ya' : 'Tidak')."\n";
                                }
                            }, "pemesanan-terpilih-".now()->format('YmdHis').".csv");
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('booking_date', 'desc');
    }
}
