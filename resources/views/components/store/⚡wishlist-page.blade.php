<?php
namespace App\Livewire\Store;
use Livewire\Component;
use App\Models\Wishlist;
use Livewire\Attributes\Layout;    

new class extends Component
{
    #[Layout('layouts.store')]
    public function removeWishlist($id)
    {
        Wishlist::where(

            'user_id',

            auth()->id()

        )->where(

            'id',

            $id

        )->delete();

        session()->flash(

            'success',

            'Wishlist removed.'
        );
    }

    public function with(): array
    {
        $wishlists = Wishlist::with(

            'product'

        )->where(

            'user_id',

            auth()->id()

        )->latest()->get();

        return [
            'wishlists' =>
                $wishlists
        ];
    }
};
?>

<div>

    <h1 class="text-4xl font-bold mb-10">

        My Wishlist

    </h1>

    @if(session()->has('success'))

        <div
            class="bg-green-100 text-green-700 p-4 rounded mb-5">

            {{ session('success') }}

        </div>

    @endif

    <div
        class="grid grid-cols-1 md:grid-cols-4 gap-6">

        @forelse($wishlists as $wishlist)

            <div
                class="bg-white rounded shadow p-4">

                @if(
                    $wishlist->product
                        ?->getFirstMediaUrl(
                            'products'
                        )
                )

                    <img
                        src="{{
                            $wishlist->product
                                ->getFirstMediaUrl(
                                    'products'
                                )
                        }}"
                        class="w-full h-52 object-cover rounded mb-3"
                    >

                @endif

                <h2
                    class="text-xl font-bold mb-2">

                    {{ $wishlist->product?->name }}

                </h2>

                <p
                    class="text-lg font-bold mb-3">

                    ₹{{ $wishlist->product?->price }}

                </p>

                <div class="flex gap-2">

                    <a
                        href="/products/{{ $wishlist->product?->id }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded">

                        View

                    </a>

                    <button
                        wire:click="removeWishlist({{ $wishlist->id }})"
                        class="bg-red-500 text-white px-4 py-2 rounded">

                        Remove

                    </button>

                </div>

            </div>

        @empty

            <div
                class="col-span-4 bg-white p-10 rounded shadow text-center">

                No wishlist products found.

            </div>

        @endforelse

    </div>

</div>