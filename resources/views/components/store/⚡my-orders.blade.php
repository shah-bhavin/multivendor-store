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

                            <div class="bg-white p-6 rounded shadow mt-6">

                                <h2 class="text-2xl font-bold mb-4">

                                    Delivery Status

                                </h2>

                                <div
                                    class="inline-block px-4 py-2 rounded bg-blue-100 text-blue-700">

                                    {{ $order->delivery_status }}

                                </div>

                                @if($order->tracking_number)

                                    <div class="mt-4">

                                        Tracking Number:

                                        <strong>

                                            {{ $order->tracking_number }}

                                        </strong>

                                    </div>

                                @endif

                            </div>

                        </td>

                        <td class="p-4">

                            {{
                                $order->created_at
                                    ->format('d M Y')
                            }}
                            <a
                                href="{{ route(

                                    'return.request',

                                    $order->id

                                ) }}"

                                class="
                                    bg-red-500
                                    text-white
                                    px-5
                                    py-3
                                    rounded
                                    inline-block
                                    mt-4
                                ">

                                Request Refund / Return

                            </a>

                            @if($order->returnRequests->count())

                                <div
                                    class="bg-yellow-100 p-5 rounded mt-6">

                                    <h3
                                        class="font-bold mb-3">

                                        Return Requests

                                    </h3>

                                    @foreach(

                                        $order->returnRequests
                                        as $request

                                    )

                                        <div class="mb-3">

                                            <strong>

                                                {{ ucfirst($request->type) }}

                                            </strong>

                                            -

                                            <span>

                                                {{ $request->status }}

                                            </span>

                                        </div>

                                    @endforeach

                                </div>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>