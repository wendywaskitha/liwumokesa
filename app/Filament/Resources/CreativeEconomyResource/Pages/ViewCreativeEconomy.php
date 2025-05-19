<?php

namespace App\Filament\Resources\CreativeEconomyResource\Pages;

use App\Filament\Resources\CreativeEconomyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;

class ViewCreativeEconomy extends ViewRecord
{
    protected static string $resource = CreativeEconomyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('visit_website')
                ->label('Kunjungi Website')
                ->icon('heroicon-o-globe-alt')
                ->color('success')
                ->url(fn ($record) => $record->website)
                ->visible(fn ($record) => !empty($record->website))
                ->openUrlInNewTab(),
            // Actions\Action::make('add_product')
            //     ->label('Tambah Produk')
            //     ->icon('heroicon-o-plus')
            //     ->color('primary')
            //     ->url(fn ($record) => CreativeEconomyResource::getUrl('products.create', ['record' => $record])),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Informasi Umum')
                    ->schema([
                        Components\TextEntry::make('name')
                            ->label('Nama Usaha')
                            ->size(Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),

                        Components\TextEntry::make('owner_name')
                            ->label('Pemilik'),

                        Components\TextEntry::make('category.name')
                            ->label('Kategori')
                            ->badge(),

                        Components\TextEntry::make('establishment_year')
                            ->label('Tahun Berdiri'),

                        Components\TextEntry::make('employees_count')
                            ->label('Jumlah Karyawan')
                            ->suffix(' orang'),

                        Components\TextEntry::make('business_hours')
                            ->label('Jam Operasional')
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(2),

                Components\Section::make('Deskripsi')
                    ->schema([
                        Components\TextEntry::make('description')
                            ->label(false)
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                Components\Grid::make(3)
                    ->schema([
                        Components\ImageEntry::make('featured_image')
                            ->label('Foto Utama')
                            ->disk('public')
                            ->height(250)
                            ->visible(fn ($record) => !empty($record->featured_image))
                            ->columnSpan(1),

                        Components\Group::make([
                            Components\Section::make('Produk')
                                ->schema([
                                    Components\TextEntry::make('products_description')
                                        ->label(false)
                                        ->markdown(),
                                ])
                                ->collapsible(),

                            Components\Section::make('Harga')
                                ->schema([
                                    Components\TextEntry::make('price_range_text')
                                        ->label(false),
                                ])
                                ->collapsible(),
                        ])->columnSpan(2),
                    ]),

                Components\Section::make('Lokasi dan Kontak')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('address')
                            ->label('Alamat')
                            ->icon('heroicon-o-map-pin'),

                        Components\TextEntry::make('district.name')
                            ->label('Kecamatan'),

                        Components\TextEntry::make('phone_number')
                            ->label('Telepon')
                            ->icon('heroicon-o-phone')
                            ->url(fn ($record) => "tel:{$record->phone_number}")
                            ->visible(fn ($record) => !empty($record->phone_number)),

                        Components\TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope')
                            ->url(fn ($record) => "mailto:{$record->email}")
                            ->visible(fn ($record) => !empty($record->email)),

                        Components\TextEntry::make('website')
                            ->label('Website')
                            ->icon('heroicon-o-globe-alt')
                            ->url(fn ($record) => $record->website)
                            ->openUrlInNewTab()
                            ->visible(fn ($record) => !empty($record->website)),

                        Components\TextEntry::make('social_media')
                            ->label('Media Sosial')
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->visible(fn ($record) => !empty($record->social_media)),
                    ]),

                Components\Section::make('Peta Lokasi')
                    ->schema([
                        Components\ViewEntry::make('map')
                            ->view('filament.infolists.components.map-viewer')
                            ->state(fn ($record) => [
                                'latitude' => $record->latitude,
                                'longitude' => $record->longitude,
                                'name' => $record->name,
                                'address' => $record->address,
                            ])
                            ->columnSpanFull(),
                    ]),

                Components\Section::make('Workshop & Pelatihan')
                    ->schema([
                        Components\IconEntry::make('has_workshop')
                            ->label('Tersedia Workshop')
                            ->boolean(),

                        Components\IconEntry::make('provides_training')
                            ->label('Menyediakan Pelatihan')
                            ->boolean(),

                        Components\TextEntry::make('workshop_information')
                            ->label('Informasi Workshop')
                            ->markdown()
                            ->visible(fn ($record) => $record->has_workshop && !empty($record->workshop_information))
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Components\IconEntry::make('has_direct_selling')
                            ->label('Menjual Langsung ke Konsumen')
                            ->boolean(),

                        Components\IconEntry::make('accepts_credit_card')
                            ->label('Menerima Kartu Kredit')
                            ->boolean(),

                        Components\IconEntry::make('shipping_available')
                            ->label('Tersedia Pengiriman')
                            ->boolean(),

                        Components\IconEntry::make('is_verified')
                            ->label('Terverifikasi')
                            ->boolean(),
                    ])
                    ->columns(4)
                    ->collapsed(),

                Components\Section::make('Galeri Foto')
                    ->schema([
                        Components\RepeatableEntry::make('galleries')
                            ->label(false)
                            ->schema([
                                Components\ImageEntry::make('file_path')
                                    ->label(false)
                                    ->disk('public')
                                    ->height(200),

                                Components\TextEntry::make('caption')
                                    ->label('Keterangan')
                                    ->size('sm'),
                            ])
                            ->grid(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
