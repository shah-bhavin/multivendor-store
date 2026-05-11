<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_name')
                    ->required(),
                TextInput::make('customer_email')
                    ->email()
                    ->required(),
                TextInput::make('customer_phone')
                    ->tel()
                    ->required(),
                Textarea::make('customer_address')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('payment_id'),
                TextInput::make('payment_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('shipping_name'),
                TextInput::make('shipping_phone')
                    ->tel(),
                Textarea::make('shipping_address')
                    ->columnSpanFull(),
                TextInput::make('shipping_city'),
                TextInput::make('shipping_state'),
                TextInput::make('shipping_country')
                    ->required()
                    ->default('India'),
                TextInput::make('shipping_pincode'),
                TextInput::make('shipping_charge')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('shipping_method')
                    ->required()
                    ->default('Standard'),
                DateTimePicker::make('shipped_at'),
                DateTimePicker::make('delivered_at'),
                TextInput::make('tracking_number'),
                
                Select::make('delivery_status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Shipped' => 'Shipped',
                        'Out For Delivery' => 'Out For Delivery',
                        'Delivered' => 'Delivered',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->live() // Required to trigger the logic immediately
                    ->afterStateUpdated(function ($state, $set, $record) {
                        if ($state === 'Shipped') {
                            $date = now();
                            $set('shipped_at', $date); // Updates the UI
                            $record?->update(['shipped_at' => $date]); // Updates the DB
                        }

                        if ($state === 'Delivered') {
                            $date = now();
                            $set('delivered_at', $date);
                            $record?->update(['delivered_at' => $date]);
                        }
                    }),

                
                TextInput::make(
                    'tracking_number'
                ),
            ]);

            
    }
}
