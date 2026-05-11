<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

use App\Models\OrderItem;

class VendorRevenueChart extends ChartWidget
{
    protected ?string $heading = 'Vendor Revenue Chart';

    protected function getData(): array
    {
        $vendorId = auth()->id();

        $data = [];
        for ($i = 5; $i >= 0; $i--) {

            $month = now()
                ->subMonths($i);

            $revenue = OrderItem::whereHas(

                'product',

                function ($query)
                use ($vendorId) {

                    $query->where(

                        'user_id',

                        $vendorId
                    );
                }

            )

                ->whereMonth(

                    'created_at',

                    $month->month

                )

                ->whereYear(

                    'created_at',

                    $month->year

                )

                ->sum('price');

            $data[] = $revenue;
        }

        return [

            'datasets' => [

                [

                    'label' =>
                    'Revenue',

                    'data' =>
                    $data,
                ],
            ],

            'labels' => [

                now()->subMonths(5)->format('M'),

                now()->subMonths(4)->format('M'),

                now()->subMonths(3)->format('M'),

                now()->subMonths(2)->format('M'),

                now()->subMonths(1)->format('M'),

                now()->format('M'),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    public static function canView(): bool
    {
        return auth()->user()
            ?->hasRole('vendor');
    }
}
