<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;

use App\Models\Product;

use App\Models\User;
class RevenueStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $revenue = Order::sum(
            'total_amount'
        );

        $orders = Order::count();
        $products = Product::count();
        $customers = User::whereHas(
            'roles',

            function ($query) {

                $query->where(
                    'name',
                    'customer'
                );
            }
        )->count();

        return [
            Stat::make(

                'Total Revenue -plus',

                '₹' . number_format(
                    $revenue,
                    2
                )
            )

            ->description(
                'Marketplace earnings'
            )

            ->color('success'),
            Stat::make(

                'Total Orders',

                $orders
            )

            ->description(
                'All customer orders'
            )

            ->color('primary'),

            Stat::make(

                'Products',

                $products
            )

            ->description(
                'Marketplace products'
            )

            ->color('warning'),

            Stat::make(

                'Customers',

                $customers
            )

            ->description(
                'Registered customers'
            )

            ->color('info'),
        ];

    }
}
