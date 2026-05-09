<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Sales';

    protected function getData(): array
    {
        $months = [];

        $sales = [];

        for ($i = 1; $i <= 12; $i++) {

            $months[] = date(
                'M',
                mktime(0, 0, 0, $i, 1)
            );

            /*
        |--------------------------------------------------------------------------
        | ADMIN SALES
        |--------------------------------------------------------------------------
        */

        if (auth()->user()->hasRole('admin')) {

            $sales[] = Order::whereMonth(
                'created_at',
                $i
            )
                ->where(
                    'payment_status',
                    'paid'
                )
                ->sum('total_amount');
        }

            /*
        |--------------------------------------------------------------------------
        | VENDOR SALES
        |--------------------------------------------------------------------------
        */ elseif (auth()->user()->hasRole('vendor')) {

                $sales[] = \App\Models\OrderItem::query()

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
                        function ($q) use ($i) {

                            $q->whereMonth(
                                'created_at',
                                $i
                            )
                                ->where(
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
            }
        }

        return [

            'datasets' => [[

                'label' => 'Sales',

                'data' => $sales,

            ]],

            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
