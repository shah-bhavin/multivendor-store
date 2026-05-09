<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentOrders extends TableWidget
{
    public function table(Table $table): Table
    {
        $query = Order::query();
        if (auth()->user()->hasRole('vendor')) {

            $query->whereHas(
                'items.product',
                function ($q) {

                    $q->where(
                        'user_id',
                        auth()->id()
                    );
                }
            );
        }
        return $table
            ->query(

                $query->latest()
            )
            ->columns([

                TextColumn::make('id')
                    ->label('Order ID'),

                TextColumn::make(
                    'customer_name'
                ),

                TextColumn::make(
                    'total_amount'
                )
                ->money('INR'),

                TextColumn::make(
                    'payment_status'
                )->badge(),

                TextColumn::make(
                    'status'
                )->badge(),

                TextColumn::make(
                    'created_at'
                )->dateTime(),
            ]);
    }
}
