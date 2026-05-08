<?php
namespace App\Livewire\Store;
use Livewire\Component;

new class extends Component
{
    #[Layout('layouts.store')]
    public $cart = [];

    public function mount()
    {
        $this->cart = session()
            ->get('cart', []);
    }

    public function increaseQty($productId)
    {
        $this->cart[$productId]['quantity']++;

        session([
            'cart' => $this->cart
        ]);
    }

    public function decreaseQty($productId)
    {
        if ($this->cart[$productId]['quantity'] > 1) {

            $this->cart[$productId]['quantity']--;
        }

        session([
            'cart' => $this->cart
        ]);
    }

    public function removeItem($productId)
    {
        unset($this->cart[$productId]);

        session([
            'cart' => $this->cart
        ]);
    }

    public function getTotalProperty()
    {
        return collect($this->cart)
            ->sum(function ($item) {

                return $item['price']
                    * $item['quantity'];
            });
    }

    // public function render()
    // {
    //     return view(
    //         'livewire.store.cart-page'
    //     )->layout('layouts.store');
    // }
};
?>

<div>

    <h1 class="text-4xl font-bold mb-10">

        Shopping Cart

    </h1>

    @if(count($cart) > 0)

        <div class="bg-white shadow rounded p-5">

            <table class="w-full">

                <thead>

                    <tr>

                        <th class="text-left pb-4">
                            Product
                        </th>

                        <th class="text-left pb-4">
                            Price
                        </th>

                        <th class="text-left pb-4">
                            Quantity
                        </th>

                        <th class="text-left pb-4">
                            Total
                        </th>

                        <th></th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($cart as $item)

                        <tr class="border-t">

                            <td class="py-4">

                                <div class="flex items-center gap-4">

                                    <img
                                        src="{{ $item['image'] }}"
                                        width="80"
                                        class="rounded"
                                    >

                                    {{ $item['name'] }}

                                </div>

                            </td>

                            <td>

                                ₹{{ $item['price'] }}

                            </td>

                            <td>

                                <div class="flex items-center gap-3">

                                    <button
                                        wire:click="decreaseQty({{ $item['id'] }})"
                                        class="bg-gray-200 px-3 py-1">

                                        -

                                    </button>

                                    {{ $item['quantity'] }}

                                    <button
                                        wire:click="increaseQty({{ $item['id'] }})"
                                        class="bg-gray-200 px-3 py-1">

                                        +

                                    </button>

                                </div>

                            </td>

                            <td>

                                ₹{{ $item['price'] * $item['quantity'] }}

                            </td>

                            <td>

                                <button
                                    wire:click="removeItem({{ $item['id'] }})"
                                    class="bg-red-500 text-white px-3 py-1">

                                    Remove

                                </button>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            <div class="mt-10 flex justify-between items-center">

                <h2 class="text-2xl font-bold">

                    Total:
                    ₹{{ $this->total }}

                </h2>

                <a href="/checkout"
                   class="bg-blue-500 text-white px-6 py-3">

                    Proceed To Checkout

                </a>

            </div>

        </div>

    @else

        <div class="bg-white p-10 rounded shadow">

            Cart is empty

        </div>

    @endif

</div>