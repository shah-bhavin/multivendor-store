<?php

namespace App\Livewire\Store;
use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;


new class extends Component
{
    // public Product $product;

    // public function mount(Product $product)
    // {
    //     $this->product = $product;
    // }

    // public function render()
    // {
    //     return view(
    //         'livewire.store.product-details'
    //     )->layout('layouts.store');
    // }

    #[Layout('layouts.store')]
    public function with(): array
    {
        return [
            'product' => Product::with('category')->first(),
        ];
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

        </p>

        <button
            class="bg-blue-500 text-white px-6 py-3">

            Add To Cart

        </button>

    </div>

</div>