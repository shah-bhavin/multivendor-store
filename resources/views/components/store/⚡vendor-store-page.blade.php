<?php
namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;    

new class extends Component
{
    #[Layout('layouts.store')]
    public User $vendor;

    public function mount($slug)
    {
        $this->vendor = User::where(

            'store_slug',

            $slug

        )->firstOrFail();
    }

    public function with(): array
    {
        $products = $this->vendor

            ->products()

            ->latest()

            ->paginate(12);

        return [
                'products' =>
                    $products
            ];
    }
};
?>

<div>

    {{-- Store Banner --}}

    <div
        class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl p-10 mb-10 text-white">

        <h1
            class="text-5xl font-bold mb-4">

            {{ $vendor->store_name }}

        </h1>

        <p
            class="text-lg max-w-3xl">

            {{ $vendor->store_description }}

        </p>

    </div>

    {{-- Store Stats --}}

    <div
        class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">

        <div
            class="bg-white rounded shadow p-6">

            <h2
                class="text-3xl font-bold">

                {{ $vendor->products()->count() }}

            </h2>

            <p class="text-gray-600">

                Products

            </p>

        </div>

        <div
            class="bg-white rounded shadow p-6">

            <h2
                class="text-3xl font-bold">

                {{ $vendor->reviews()->count() }}

            </h2>

            <p class="text-gray-600">

                Reviews

            </p>

        </div>

        <div
            class="bg-white rounded shadow p-6">

            <h2
                class="text-3xl font-bold">

                {{ $vendor->products()->sum('stock') }}

            </h2>

            <p class="text-gray-600">

                Total Stock

            </p>

        </div>

    </div>

    {{-- Products --}}

    <div
        class="grid grid-cols-1 md:grid-cols-4 gap-6">

        @forelse($products as $product)

            <div
                class="bg-white rounded shadow p-4">

                @if(
                    $product->getFirstMediaUrl(
                        'products'
                    )
                )

                    <img
                        src="{{
                            $product->getFirstMediaUrl(
                                'products'
                            )
                        }}"
                        class="w-full h-52 object-cover rounded mb-3"
                    >

                @endif

                <h2
                    class="text-xl font-bold mb-2">

                    {{ $product->name }}

                </h2>

                <p
                    class="text-lg font-bold mb-3">

                    ₹{{ $product->price }}

                </p>

                <a
                    href="/products/{{ $product->id }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded inline-block">

                    View Product

                </a>

            </div>

        @empty

            <div
                class="col-span-4 bg-white rounded shadow p-10 text-center">

                No products found.

            </div>

        @endforelse

    </div>

    {{-- Pagination --}}

    <div class="mt-10">

        {{ $products->links() }}

    </div>

</div>