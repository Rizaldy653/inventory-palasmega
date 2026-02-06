<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Directory;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('code')->required(),
                TextInput::make('type'),
                TextInput::make('brand'),
                TextInput::make('load'),
                Select::make('unit')
                    ->options([
                        'kN' => 'kN',
                        'kg' => 'kg',
                        'm' => 'Meter',
                        'L' => 'Liter',
                    ]),
                TextInput::make('color'),
                TextInput::make('added_at')->required(),
                Select::make('category_id')
                    ->label('Category')    
                    ->relationship('category', 'name')
                    ->searchable()
                    ->required()
                    ->preload(),
                Select::make('status')
                    ->options([
                        'good' => 'Good',
                        'need_repaiar' => 'Need Repaiar',
                        'broken' => 'Broken',
                ])->required(),
                Select::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                ])->required(),
                Select::make('groups')
                    ->label('Groups')    
                    ->relationship('groups', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                TextInput::make('note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable(),    
                TextColumn::make('name')->searchable(),    
                TextColumn::make('type')->searchable(),    
                TextColumn::make('brand')->searchable(),
                TextColumn::make('color'),
                TextColumn::make('load'),
                TextColumn::make('unit'),
                TextColumn::make('added_at')->searchable(),
                TextColumn::make('category.name'),
                TextColumn::make('status'),
                TextColumn::make('is_active')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive'),
                TextColumn::make('groups.name')
                    ->label('Group')
                    ->listWithLineBreaks(),
                TextColumn::make('note'),
                // FileUpload::make('image')
                //     ->label('Image')
                //     ->image()
                //     ->directory('item_images')
                //     ->visibility('public'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                SelectFilter::make('groups')
                    ->label('Group')
                    ->relationship('groups', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
            'view' => Pages\ViewItemDetails::route('/{record}'),
        ];
    }
}
