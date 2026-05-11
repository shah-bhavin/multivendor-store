<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer_name')
                    ->searchable(),
                TextColumn::make('customer_email')
                    ->searchable(),
                TextColumn::make('customer_phone')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_id')
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->searchable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_name')
                    ->searchable(),
                TextColumn::make('shipping_phone')
                    ->searchable(),
                TextColumn::make('shipping_city')
                    ->searchable(),
                TextColumn::make('shipping_state')
                    ->searchable(),
                TextColumn::make('shipping_country')
                    ->searchable(),
                TextColumn::make('shipping_pincode')
                    ->searchable(),
                TextColumn::make('shipping_charge')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_method')
                    ->searchable(),
                
                TextColumn::make('shipped_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('delivered_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('tracking_number')
                    ->searchable(),
                TextColumn::make('delivery_status')
                    ->badge()
                    ->colors([

                        'warning' => 'Pending',

                        'info' => 'Processing',

                        'primary' => 'Shipped',

                        'success' => 'Delivered',

                        'danger' => 'Cancelled',
                    ])
                ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
