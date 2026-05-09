<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

use Filament\Widgets\StatsOverviewWidget
    as BaseWidget;

use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        /*
        |--------------------------------------------------------------------------
        | ADMIN DATA
        |--------------------------------------------------------------------------
        */

        if (auth()->user()->hasRole('admin')) {

            $totalRevenue = Order::where(
                'payment_status',
                'paid'
            )->sum('total_amount');

            $totalOrders = Order::where(['payment_status'=>'paid'])->count();

            $totalProducts = Product::count();

            $totalVendors = User::role(
                'vendor'
            )->count();
        }

        /*
        |--------------------------------------------------------------------------
        | VENDOR DATA
        |--------------------------------------------------------------------------
        */

        else {

            /*
            |--------------------------------------------------------------------------
            | Vendor Revenue
            |--------------------------------------------------------------------------
            */

            $totalRevenue = OrderItem::query()

                ->whereHas(
                    'product',
                    function ($q) {

                        $q->where(
                            'user_id',
                            auth()->id()
                        );
                    }
                )

                ->whereHas(
                    'order',
                    function ($q) {

                        $q->where(
                            'payment_status',
                            'paid'
                        );
                    }
                )

                ->get()

                ->sum(function ($item) {

                    return
                        $item->price
                        * $item->quantity;
                });

            /*
            |--------------------------------------------------------------------------
            | Vendor Orders
            |--------------------------------------------------------------------------
            */

            $totalOrders = Order::whereHas(
                'items.product',
                function ($q) {

                    $q->where(
                        'user_id',
                        auth()->id()
                    );
                }
            )->count();

            /*
            |--------------------------------------------------------------------------
            | Vendor Products
            |--------------------------------------------------------------------------
            */

            $totalProducts = Product::where(
                'user_id',
                auth()->id()
            )->count();

            /*
            |--------------------------------------------------------------------------
            | Vendors Count Hidden
            |--------------------------------------------------------------------------
            */

            $totalVendors = 0;
        }

        return [

            /*
            |--------------------------------------------------------------------------
            | Revenue
            |--------------------------------------------------------------------------
            */

            Stat::make(

                'Total Revenue',

                '₹' . number_format(
                    $totalRevenue
                )
            )

                ->description(
                    'Paid Revenue'
                )

                ->color('success'),

            /*
            |--------------------------------------------------------------------------
            | Orders
            |--------------------------------------------------------------------------
            */

            Stat::make(

                'Total Orders',

                $totalOrders
            )

                ->description(
                    'Customer Orders'
                )

                ->color('primary'),

            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */

            Stat::make(

                'Total Products',

                $totalProducts
            )

                ->description(
                    'Store Products'
                )

                ->color('warning'),

            /*
            |--------------------------------------------------------------------------
            | Vendors
            |--------------------------------------------------------------------------
            */

            ...(auth()->user()->hasRole('admin')
                ? [

                    Stat::make(

                        'Total Vendors',

                        $totalVendors
                    )

                    ->description(
                        'Registered Vendors'
                    )

                    ->color('info'),
                ]
                : []),
        ];
    }
}