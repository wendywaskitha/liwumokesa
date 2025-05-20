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
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
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

                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah Peserta')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->required(),

                Forms\Components\Select::make('booking_status')
                    ->label('Status Pemesanan')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'confirmed' => 'Terkonfirmasi',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\Select::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Dibayar',
                        'paid' => 'Sudah Dibayar',
                        'refunded' => 'Direfund',
                    ])
                    ->default('unpaid')
                    ->required(),

                Forms\Components\TextInput::make('total_price')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\FileUpload::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->directory('payment-proofs')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
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

                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Tanggal Booking')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah Peserta')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('booking_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('is_used')
                    ->label('Status Tiket')
                    ->formatStateUsing(fn ($record) =>
                        $record->is_used
                            ? "Digunakan pada " . $record->used_at?->format('d M Y H:i')
                            : "Belum Digunakan"
                    )
                    ->badge()
                    ->color(fn ($record) => $record->is_used ? 'success' : 'warning'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('booking_status')
                    ->label('Status Pemesanan')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'confirmed' => 'Terkonfirmasi',
                        'cancelled' => 'Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Dibayar',
                        'paid' => 'Sudah Dibayar',
                        'refunded' => 'Direfund',
                    ]),
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
                        ->action(fn (Booking $record) => $record->update([
                            'booking_status' => 'confirmed',
                            'payment_status' => 'paid',
                        ]))
                        ->visible(fn (Booking $record): bool => $record->booking_status === 'pending'),

                    Tables\Actions\Action::make('verifyTicket')
                        ->label('Verifikasi Tiket')
                        ->icon('heroicon-o-ticket')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Tiket')
                        ->modalDescription('Apakah Anda yakin ingin memverifikasi tiket ini?')
                        ->modalSubmitActionLabel('Ya, Verifikasi')
                        ->visible(fn ($record) =>
                            !$record->is_used &&
                            $record->booking_status === 'confirmed' &&
                            $record->payment_status === 'paid'
                        )
                        ->action(function ($record) {
                            $record->update([
                                'is_used' => true,
                                'used_at' => now()
                            ]);

                            Notification::make()
                                ->title('Tiket berhasil diverifikasi')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('cancel')
                        ->label('Batalkan Pemesanan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Booking $record) => $record->update(['booking_status' => 'cancelled']))
                        ->visible(fn (Booking $record): bool => $record->booking_status === 'pending'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('booking_date', 'desc');
    }
}
