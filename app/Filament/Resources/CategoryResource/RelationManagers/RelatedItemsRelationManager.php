<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RelatedItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'relatedItems';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Item Terkait';

    // We need to manually handle this in the viewData method
    public function viewData(): array
    {
        $category = $this->getOwnerRecord();
        $data = [
            'items' => [],
            'type' => $category->type
        ];

        switch($category->type) {
            case 'destinasi':
                $data['items'] = $category->destinations;
                $data['label'] = 'Destinasi';
                break;
            case 'kuliner':
                $data['items'] = $category->culinaries;
                $data['label'] = 'Kuliner';
                break;
            case 'ekonomi-kreatif':
                $data['items'] = $category->creativeEconomies;
                $data['label'] = 'Ekonomi Kreatif';
                break;
            case 'event':
                $data['items'] = $category->events;
                $data['label'] = 'Event';
                break;
        }

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // No actions here
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(function ($record) {
                        $category = $this->getOwnerRecord();
                        $resourcePath = match($category->type) {
                            'destinasi' => 'destinations',
                            'kuliner' => 'culinaries',
                            'ekonomi-kreatif' => 'creative-economies',
                            'event' => 'events',
                            default => null
                        };

                        if (!$resourcePath) return '#';

                        return url("/admin/$resourcePath/{$record->id}");
                    }),
            ])
            ->bulkActions([
                // No bulk actions
            ]);
    }
}
