<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers\UsersRelationManager;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Pengguna';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Role')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Role')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(500),
                    ]),

                Section::make('Hak Akses')
                    ->schema([
                        Select::make('permissions')
                            ->label('Permissions')
                            ->multiple()
                            ->options([
                                'manage_users' => 'Kelola Pengguna',
                                'manage_destinations' => 'Kelola Destinasi',
                                'manage_accommodations' => 'Kelola Akomodasi',
                                'manage_transportations' => 'Kelola Transportasi',
                                'manage_culinaries' => 'Kelola Kuliner',
                                'manage_creative_economies' => 'Kelola Ekonomi Kreatif',
                                'manage_cultural_heritages' => 'Kelola Warisan Budaya',
                                'manage_events' => 'Kelola Event',
                                'manage_travel_packages' => 'Kelola Paket Wisata',
                                'manage_districts' => 'Kelola Kecamatan',
                                'manage_categories' => 'Kelola Kategori',
                                'manage_amenities' => 'Kelola Fasilitas Umum',
                                'manage_reviews' => 'Kelola Ulasan',
                                'manage_galleries' => 'Kelola Galeri',
                                'manage_settings' => 'Kelola Pengaturan',
                                'view_statistics' => 'Lihat Statistik',
                            ])
                            ->searchable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Role')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('users_count')
                    ->label('Jumlah Pengguna')
                    ->counts('users')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, Role $record) {
                        if ($record->users()->count() > 0) {
                            $action->cancel();
                            $action->notify('warning', 'Role tidak bisa dihapus karena masih memiliki pengguna.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
