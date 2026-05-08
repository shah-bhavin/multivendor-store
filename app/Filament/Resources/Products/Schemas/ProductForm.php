<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₹'),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('status')
                    ->required(),
                // TextInput::make('category_id')
                //     ->numeric(),
                Select::make('category_id')
                    ->relationship('category', 'name') // 'category' is the relation name in your Model
                    ->searchable()
                    ->preload()
                    ->required()
            ]);
    }
}
