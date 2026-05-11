<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;

use Filament\Tables;

use Filament\Tables\Table;

use Filament\Widgets\TableWidget
as BaseWidget;

class VendorLatestOrders
extends BaseWidget
{
    protected int|string|array $columnSpan =
    'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(

                OrderItem::query()

                    ->whereHas(

                        'product',

                        function ($query) {

                            $query->where(

                                'user_id',

                                auth()->id()
                            );
                        }

                    )

                    ->latest()
            )

            ->columns([

                Tables\Columns\TextColumn::make(
                    'order.id'
                )
                    ->label('Order ID'),

                Tables\Columns\TextColumn::make(
                    'product.name'
                )
                    ->label('Product')
                    ->searchable(),

                Tables\Columns\TextColumn::make(
                    'quantity'
                ),

                Tables\Columns\TextColumn::make(
                    'total'
                )
                    ->money('INR'),

                Tables\Columns\TextColumn::make(
                    'created_at'
                )
                    ->since(),
            ]);
    }
    public static function canView(): bool
    {
        return auth()->user()
            ?->hasRole('vendor');
    }
    
}
