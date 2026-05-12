<?php

namespace App\Filament\Resources\ReturnRequests\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReturnRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('order_id')
                    ->required()
                    ->numeric(),
                TextInput::make('type')
                    ->required()
                    ->default('refund'),
                Textarea::make('reason')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('refund_amount')
                    ->numeric(),
                Select::make('status')
                    ->options([

                        'warning' =>
                            'Pending',

                        'success' =>
                            'Approved',

                        'danger' =>
                            'Rejected',

                        'primary' =>
                            'Refunded',
                    ])
                    ->afterStateUpdated(function ($state, $record) {
                        // Check for 'success' or 'primary' instead of the labels
                        if (in_array($state, ['success', 'primary'])) {
                            $record->update([
                                'processed_at' => now(),
                            ]);
                        }
                    })
                    ->live()
                    ->required()
                    ->default('Pending'),
                Textarea::make('admin_note')
                    ->columnSpanFull(),
                DateTimePicker::make('processed_at'),
            ]);
    }
}
