<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VendorStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $vendorId = auth()->id();

        $productsCount = Product::where(

            'user_id',

            $vendorId

        )->count();

        $ordersCount = OrderItem::whereHas(

            'product',

            function ($query) use ($vendorId) {

                $query->where(

                    'user_id',

                    $vendorId
                );
            }

        )->count();

        $revenue = OrderItem::whereHas(

            'product',

            function ($query) use ($vendorId) {

                $query->where(

                    'user_id',

                    $vendorId
                );
            }

        )->sum('price');

        return [

            Stat::make(

                'Total Products',

                $productsCount
            )

                ->description(
                    'Vendor products'
                )

                ->color('success'),

            Stat::make(

                'Total Orders',

                $ordersCount
            )

                ->description(
                    'Orders received'
                )

                ->color('info'),

            Stat::make(

                'Total Revenue',

                '₹' . number_format(
                    $revenue,
                    2
                )
            )

                ->description(
                    'Vendor earnings'
                )

                ->color('warning'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()
            ?->hasRole('vendor');
    }
}
