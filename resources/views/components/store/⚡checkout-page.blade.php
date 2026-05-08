<?php

namespace App\Livewire\Store;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Models\OrderItem;
new class extends Component
{
    #[Layout('layouts.store')]
    public $customer_name;

    public $customer_email;

    public $customer_phone;

    public $customer_address;

    public $cart = [];

    public function mount()
    {
        $this->cart = session()
            ->get('cart', []);
    }
    public function placeOrder()
    {
        $this->validate([

            'customer_name' =>
                'required',

            'customer_email' =>
                'required|email',

            'customer_phone' =>
                'required',

            'customer_address' =>
                'required',
        ]);

        $total = collect($this->cart)
            ->sum(function ($item) {

                return $item['price']
                    * $item['quantity'];
            });

        $order = Order::create([

            'customer_name' =>
                $this->customer_name,

            'customer_email' =>
                $this->customer_email,

            'customer_phone' =>
                $this->customer_phone,

            'customer_address' =>
                $this->customer_address,

            'total_amount' =>
                $total,

            'status' =>
                'pending',
        ]);

        foreach ($this->cart as $item) {

            OrderItem::create([

                'order_id' =>
                    $order->id,

                'product_id' =>
                    $item['id'],

                'quantity' =>
                    $item['quantity'],

                'price' =>
                    $item['price'],
            ]);
        }

        session()->forget('cart');

        return redirect('/')
            ->with(
                'success',
                'Order Placed Successfully'
            );
    }
};
?>

<div>

    <h1 class="text-4xl font-bold mb-10">

        Checkout

    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        {{-- Checkout Form --}}

        <div class="bg-white p-6 rounded shadow">

            <h2 class="text-2xl font-bold mb-5">

                Customer Information

            </h2>

            <div class="space-y-5">

                <input
                    type="text"
                    wire:model="customer_name"
                    placeholder="Full Name"
                    class="w-full border p-3 rounded"
                >

                <input
                    type="email"
                    wire:model="customer_email"
                    placeholder="Email"
                    class="w-full border p-3 rounded"
                >

                <input
                    type="text"
                    wire:model="customer_phone"
                    placeholder="Phone"
                    class="w-full border p-3 rounded"
                >

                <textarea
                    wire:model="customer_address"
                    placeholder="Address"
                    class="w-full border p-3 rounded"
                ></textarea>

                <button
                    wire:click="placeOrder"
                    class="bg-green-500 text-white px-6 py-3">

                    Place Order

                </button>

            </div>

        </div>

        {{-- Order Summary --}}

        <div class="bg-white p-6 rounded shadow">

            <h2 class="text-2xl font-bold mb-5">

                Order Summary

            </h2>

            @foreach($cart as $item)

                <div
                    class="flex justify-between border-b py-3">

                    <div>

                        {{ $item['name'] }}
                        ×
                        {{ $item['quantity'] }}

                    </div>

                    <div>

                        ₹{{ $item['price'] * $item['quantity'] }}

                    </div>

                </div>

            @endforeach

            <div class="mt-5 text-2xl font-bold">

                Total:
                ₹{{
                    collect($cart)->sum(
                        fn($item) =>
                            $item['price']
                            * $item['quantity']
                    )
                }}

            </div>

        </div>

    </div>

</div>