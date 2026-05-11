<?php

namespace App\Filament\Resources\Conversations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ConversationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('vendor_id')
                    ->required()
                    ->numeric(),
                TextInput::make('product_id')
                    ->numeric(),
            ]);
    }
}
