<?php
namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;


new class extends Component
{   
    #[Layout('layouts.store')]
    public function with(): array
    {
        return [
            'products' => Product::latest()->take(8)->get(),
        ];
    }
};
?>

<div>

    <h1 class="text-4xl font-bold mb-10">

        

    {{ __('messages.latest_products') }}



    </h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        @foreach($products as $product)

            <div class="bg-white rounded shadow p-4">

                @if($product->getFirstMediaUrl('products'))

                    <img
                        src="{{ $product->getFirstMediaUrl('products') }}"
                        class="w-full h-52 object-cover mb-3 rounded"
                    >

                @endif

                <h2 class="text-xl font-bold">

                    {{ $product->name }}

                </h2>

                <p class="text-gray-500 mb-2">

                    {{ $product->category?->name }}

                </p>

                <p class="text-lg font-bold mb-3">

                    ₹{{ $product->price }}

                </p>

                <a href="/products/{{ $product->slug }}"
                   class="bg-blue-500 text-white px-4 py-2 inline-block">

                    View Product

                </a>

            </div>

        @endforeach
            
    </div>
    <livewire:store.newsletter-subscribe />
    <livewire:store.ai-chatbot />
</div>