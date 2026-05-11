<?php

namespace App\Filament\Resources\Messages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('conversation_id')
                    ->required()
                    ->numeric(),
                TextInput::make('sender_id')
                    ->required()
                    ->numeric(),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
