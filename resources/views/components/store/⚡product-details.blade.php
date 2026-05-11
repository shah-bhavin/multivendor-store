<?php

namespace App\Livewire\Store;
use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed; 

new class extends Component
{
    #[Layout('layouts.store')]
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function addToCart(){
        $product = Product::first(); 

        $cart = session()->get('cart', []);

        $productId = $this->product->id;

        if (isset($cart[$productId])) {

            $cart[$productId]['quantity']++;

        } else {

            $cart[$productId] = [

                'id' => $this->product->id,

                'name' => $this->product->name,

                'price' => $this->product->price,

                'image' =>
                    $this->product
                        ->getFirstMediaUrl('products'),

                'quantity' => 1,
            ];
        }

        session([
            'cart' => $cart
        ]);

        session()->flash(
            'success',
            'Product Added To Cart'
        );
    }
};
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-10">

    <div>

        @foreach($product->getMedia('products') as $image)

            <img
                src="{{ $image->getUrl() }}"
                class="w-full rounded mb-4"
            >

        @endforeach

    </div>

    <div>

        <h1 class="text-4xl font-bold mb-5">

            {{ $product->name }}

        </h1>

        <p class="text-gray-500 mb-3">

            Category:
            {{ $product->category?->name }}

        </p>

        <p class="text-2xl font-bold mb-5">

            ₹{{ $product->price }}

        </p>

        <p class="mb-5">

            {{ $product->description }}

        </p>

        <p class="mb-5">

            Vendor:
            {{ $product->user?->name }}
            <div class="mt-5">

                <a
                    href="/shop/{{ $product->user?->store_slug }}"
                    class="text-blue-500 font-bold">

                    Visit Vendor Store

                </a>

            </div>
        </p>

        @if(session()->has('success'))

            <div class="bg-green-500 text-white p-3 mb-5">

                {{ session('success') }}

            </div>

        @endif

        <button
            wire:click="addToCart"
            class="bg-blue-500 text-white px-6 py-3">

            Add To Cart

        </button>

    </div>
    <livewire:store.product-reviews
        :product="$product"
    />
    <livewire:store.chat-box
        :product="$product"
    />
</div>