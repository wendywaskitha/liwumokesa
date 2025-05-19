<?php

namespace App\Filament\Resources\CreativeEconomyResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\ImportAction;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Imports\ProductImporter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Produk';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->maxLength(255)
                    ->helperText('Akan dihasilkan otomatis dari nama jika dikosongkan'),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                Forms\Components\TextInput::make('discounted_price')
                    ->label('Harga Diskon')
                    ->numeric()
                    ->prefix('Rp')
                    ->lte('price')
                    ->nullable(),

                Forms\Components\TextInput::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->default(1)
                    ->required(),

                Forms\Components\TextInput::make('material')
                    ->label('Bahan')
                    ->maxLength(255),

                Forms\Components\TextInput::make('size')
                    ->label('Ukuran')
                    ->maxLength(255),

                Forms\Components\TextInput::make('weight')
                    ->label('Berat')
                    ->suffix('gram')
                    ->numeric(),

                Forms\Components\TextInput::make('dimensions')
                    ->label('Dimensi')
                    ->placeholder('contoh: 10cm x 5cm x 2cm')
                    ->maxLength(255),

                Forms\Components\TextInput::make('colors')
                    ->label('Warna yang Tersedia')
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_featured')
                    ->label('Produk Unggulan')
                    ->helperText('Tampilkan di bagian produk unggulan')
                    ->default(false),

                Forms\Components\Toggle::make('is_custom_order')
                    ->label('Pesanan Khusus')
                    ->helperText('Produk dibuat sesuai pesanan')
                    ->default(false)
                    ->reactive(),

                Forms\Components\TextInput::make('production_time')
                    ->label('Waktu Produksi')
                    ->numeric()
                    ->suffix('hari')
                    ->visible(fn (callable $get) => $get('is_custom_order')),

                Forms\Components\FileUpload::make('featured_image')
                    ->label('Foto Utama')
                    ->image()
                    ->directory('products')
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('status')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText('Nonaktifkan jika produk tidak tersedia'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Foto')
                    ->square()
                    ->disk('public'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('discounted_price')
                    ->label('Harga Diskon')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_featured')
                    ->label('Produk Unggulan')
                    ->options([
                        '1' => 'Ya',
                        '0' => 'Tidak',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Nonaktif',
                    ]),

                Tables\Filters\Filter::make('stock')
                    ->label('Produk Kosong')
                    ->query(fn (Builder $query) => $query->where('stock', '<=', 0)),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Produk'),

                // Use the correct import action from Filament Actions namespace
                // \Filament\Actions\Imports\ImportAction::make()
                //     ->label('Import Produk')
                //     ->icon('heroicon-o-arrow-up-tray')
                //     ->importer(ProductImporter::class)
                //     ->options([
                //         'creativeEconomyId' => fn (RelationManager $livewire) => $livewire->ownerRecord->id,
                //     ]),

                // // Template download action
                // Tables\Actions\Action::make('download_template')
                //     ->label('Download Template')
                //     ->icon('heroicon-o-arrow-down-tray')
                //     ->color('primary')
                //     ->url(route('download.product-template'))
                //     ->openUrlInNewTab(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('duplicate')
                        ->label('Duplikat')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function (Model $record) {
                            $duplicate = $record->replicate();
                            $duplicate->name = $duplicate->name . ' (copy)';
                            $duplicate->slug = Str::slug($duplicate->name);
                            $duplicate->save();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('setFeatured')
                        ->label('Set Unggulan')
                        ->icon('heroicon-o-star')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => true])),

                    Tables\Actions\BulkAction::make('unsetFeatured')
                        ->label('Hapus Unggulan')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn (Collection $records) => $records->each->update(['is_featured' => false])),
                ]),
            ]);
    }
}
