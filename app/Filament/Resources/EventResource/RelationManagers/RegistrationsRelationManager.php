<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class RegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    protected static ?string $title = 'Pendaftaran Peserta';
    
    protected static ?string $recordTitleAttribute = 'registration_code';

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
                    
                Forms\Components\TextInput::make('registration_code')
                    ->label('Kode Pendaftaran')
                    ->default(fn () => strtoupper(Str::random(8)))
                    ->disabled()
                    ->dehydrated(),
                    
                Forms\Components\TextInput::make('number_of_tickets')
                    ->label('Jumlah Tiket')
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
                        'attended' => 'Hadir',
                    ])
                    ->default('pending')
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('registration_date')
                    ->label('Tanggal Pendaftaran')
                    ->default(now())
                    ->required(),
                    
                Forms\Components\TextInput::make('payment_amount')
                    ->label('Jumlah Pembayaran')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                    
                Forms\Components\Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet',
                        'on_site' => 'Bayar di Tempat',
                    ])
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('payment_date')
                    ->label('Tanggal Pembayaran'),
                    
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
                Tables\Columns\TextColumn::make('registration_code')
                    ->label('Kode')
                    ->searchable()
                    ->copyable()
                    ->tooltip('Klik untuk menyalin'),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('number_of_tickets')
                    ->label('Jumlah Tiket')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('payment_amount')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Bayar')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'bank_transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet',
                        'on_site' => 'Di Tempat',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'bank_transfer' => 'primary',
                        'e_wallet' => 'success',
                        'on_site' => 'warning',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'confirmed' => 'Terkonfirmasi',
                        'cancelled' => 'Dibatalkan',
                        'attended' => 'Hadir',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'attended' => 'primary',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('registration_date')
                    ->label('Tgl Registrasi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'confirmed' => 'Terkonfirmasi',
                        'cancelled' => 'Dibatalkan',
                        'attended' => 'Hadir',
                    ]),
                    
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet',
                        'on_site' => 'Bayar di Tempat',
                    ]),
                    
                Tables\Filters\Filter::make('registration_date')
                    ->form([
                        Forms\Components\DatePicker::make('registered_from')
                            ->label('Registered From'),
                        Forms\Components\DatePicker::make('registered_until')
                            ->label('Registered Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['registered_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('registration_date', '>=', $date),
                            )
                            ->when(
                                $data['registered_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('registration_date', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Peserta'),
                    
                Tables\Actions\Action::make('printAttendanceList')
                    ->label('Cetak Daftar Hadir')
                    ->icon('heroicon-o-printer')
                    ->action(function (RelationManager $livewire) {
                        // Logic to generate and download attendance list
                        return response()->streamDownload(function () use ($livewire) {
                            $event = $livewire->getOwnerRecord();
                            $registrations = $event->registrations()->with('user')->where('status', 'confirmed')->get();
                            
                            echo "DAFTAR HADIR\n";
                            echo "Event: {$event->name}\n";
                            echo "Tanggal: " . $event->start_date->format('d/m/Y') . "\n";
                            echo "Lokasi: {$event->location}\n\n";
                            echo "No\tKode\tNama\tTiket\tTanda Tangan\n";
                            echo "----------------------------------------\n";
                            
                            $i = 1;
                            foreach ($registrations as $reg) {
                                echo "$i\t{$reg->registration_code}\t{$reg->user->name}\t{$reg->number_of_tickets}\t\n";
                                $i++;
                            }
                        }, 'daftar-hadir-' . Str::slug($livewire->getOwnerRecord()->name) . '.txt');
                    }),
                    
                Tables\Actions\Action::make('importRegistrations')
                    ->label('Import Pendaftaran')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('File CSV')
                            ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel'])
                            ->required(),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        // CSV import logic would go here
                        $livewire->notify('success', 'Data peserta berhasil diimport');
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    
                    Tables\Actions\Action::make('confirm')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($record) => $record->update(['status' => 'confirmed']))
                        ->visible(fn ($record) => $record->status === 'pending'),
                        
                    Tables\Actions\Action::make('cancel')
                        ->label('Batalkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($record) => $record->update(['status' => 'cancelled']))
                        ->visible(fn ($record) => $record->status !== 'cancelled'),
                        
                    Tables\Actions\Action::make('markAsAttended')
                        ->label('Tandai Hadir')
                        ->icon('heroicon-o-check-circle')
                        ->color('primary')
                        ->action(fn ($record) => $record->update(['status' => 'attended']))
                        ->visible(fn ($record) => $record->status === 'confirmed'),
                        
                    Tables\Actions\Action::make('sendTicket')
                        ->label('Kirim Tiket')
                        ->icon('heroicon-o-paper-airplane')
                        ->action(function ($record) {
                            // Logic to send ticket via email
                        })
                        ->visible(fn ($record) => $record->status === 'confirmed'),
                        
                    Tables\Actions\Action::make('generateQR')
                        ->label('Generate QR')
                        ->icon('heroicon-o-qr-code')
                        ->url(fn ($record) => route('generate.qr.code', $record->registration_code))
                        ->openUrlInNewTab()
                        ->visible(fn ($record) => $record->status === 'confirmed'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('bulkConfirm')
                        ->label('Konfirmasi Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'confirmed']))
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\BulkAction::make('bulkCancel')
                        ->label('Batalkan Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'cancelled']))
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\BulkAction::make('bulkMarkAttended')
                        ->label('Tandai Hadir Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('primary')
                        ->action(fn ($records) => $records->each->update(['status' => 'attended']))
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\BulkAction::make('emailTickets')
                        ->label('Kirim Tiket Terpilih')
                        ->icon('heroicon-o-paper-airplane')
                        ->action(function ($records) {
                            // Logic to send tickets to multiple users
                        })
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\BulkAction::make('exportRegistrations')
                        ->label('Export Terpilih')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            // Export logic to CSV/Excel
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
