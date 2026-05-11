<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Product;

class InventoryStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $vendorId = auth()->id();

        return [

            Stat::make(

                'Total Products',

                Product::where(

                    'user_id',

                    $vendorId

                )->count()
            ),

            Stat::make(

                'Low Stock Products',

                Product::where(

                    'user_id',

                    $vendorId

                )

                ->whereColumn(

                    'stock',

                    '<=',

                    'low_stock_alert'
                )

                ->count()
            )

            ->color('warning'),

            Stat::make(

                'Out Of Stock',

                Product::where(

                    'user_id',

                    $vendorId

                )

                ->where(

                    'stock',

                    '<=',
                    0
                )

                ->count()
            )

            ->color('danger'),
        ];
    }
}
