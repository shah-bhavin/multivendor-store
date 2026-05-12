<?php

namespace App\Livewire\Store;
use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed; 

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;

use Illuminate\Support\Facades\Cache;
new class extends Component
{
    #[Layout('layouts.store')]
    

    public Product $product;
    
    public function mount(Product $product, $slug)
    {

        $this->product = Product::where('slug', $slug)->firstOrFail();

        SEOMeta::setTitle(
            $this->product->name
        );

        SEOMeta::setDescription(
            str($this->product->description)
                ->limit(160)
        );

        SEOMeta::addKeyword([
            $this->product->name,
            'buy online',
            'marketplace',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Open Graph
        |--------------------------------------------------------------------------
        */

        OpenGraph::setTitle(

            $this->product->name
        );

        OpenGraph::setDescription(

            str($this->product->description)
                ->limit(160)
        );

        OpenGraph::setUrl(

            url()->current()
        );

        OpenGraph::addImage(

            $this->product
                ->getFirstMediaUrl(
                    'products'
                )
        );

        /*
        |--------------------------------------------------------------------------
        | JSON-LD
        |--------------------------------------------------------------------------
        */

        JsonLd::setTitle(

            $this->product->name
        );

        JsonLd::setDescription(

            str($this->product->description)
                ->limit(160)
        );
    }

    public function addToCart(){
        $product = Product::first(); 
        
        if ($this->product->isOutOfStock()) {

            session()->flash(

                'error',

                'Product is out of stock.'
            );

            return;
        }
        if (

            

            1>$this->product->stock

        ) {

            session()->flash(

                'error',

                'Requested quantity unavailable.'
            );

            return;
        }
        
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
    {{-- <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{ $product->name }}",
        "image": "{{ $product->getFirstMediaUrl('products') }}",
        "description": "{{ strip_tags($product->description) }}",
        "offers": {
            "@type": "Offer",
            "price": "{{ $product->price }}",
            "priceCurrency": "INR",
            "availability": "https://schema.org/InStock"
        }
    }
    </script> --}}

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

            {{ currency($product->price) }}

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
        @elseif(session()->has('error'))

        <div class="bg-red-500 text-white p-3 mb-5">

            {{ session('error') }}

        </div>        

        @endif
        
        <div class="mt-4">

            @if($product->isOutOfStock())

                <div
                    class="bg-red-100 text-red-700 px-4 py-2 rounded">

                    Out Of Stock

                </div>

            @elseif($product->isLowStock())

                <div
                    class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded">

                    Only {{ $product->stock }} left in stock

                </div>

            @else

                <div
                    class="bg-green-100 text-green-700 px-4 py-2 rounded">

                    In Stock

                </div>

            @endif

        </div>

        @if($product->isOutOfStock())

            <button
                disabled
                class="bg-gray-400 text-white px-6 py-3 rounded">

                Out Of Stock

            </button>

        @else

            <button
                wire:click="addToCart"
                class="bg-blue-500 text-white px-6 py-3 rounded">

                {{ __('messages.add_to_cart') }}

            </button>

        @endif

    </div>
    <livewire:store.product-reviews
        :product="$product"
    />
    <livewire:store.chat-box
        :product="$product"
    />
</div>