<?php
namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

new class extends Component
{
    #[Layout('layouts.store')]
    public function with(): array
    {
        $orders = Order::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        return [
            'orders' => $orders
        ];
    }
};
?>

<div>

    <h1 class="text-4xl font-bold mb-10">

        My Orders

    </h1>

    <div class="bg-white rounded shadow">

        <table class="w-full">

            <thead>

                <tr>

                    <th class="p-4 text-left">
                        Order ID
                    </th>

                    <th class="p-4 text-left">
                        Total
                    </th>

                    <th class="p-4 text-left">
                        Payment
                    </th>

                    <th class="p-4 text-left">
                        Status
                    </th>

                    <th class="p-4 text-left">
                        Date
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($orders as $order)

                    <tr class="border-t">

                        <td class="p-4">

                            #{{ $order->id }}

                        </td>

                        <td class="p-4">

                            ₹{{ $order->total_amount }}

                        </td>

                        <td class="p-4">

                            {{ $order->payment_status }}

                        </td>

                        <td class="p-4">

                            {{ $order->status }}

                        </td>

                        <td class="p-4">

                            {{
                                $order->created_at
                                    ->format('d M Y')
                            }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>