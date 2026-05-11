<?php
namespace App\Livewire\Store;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Conversation;

new class extends Component
{
    #[Layout('layouts.store')]
    public function render()
    {
        $conversations =
            Conversation::with([

                'product',

                'vendor',

                'customer'
            ])

            ->where(function ($query) {

                $query->where(

                    'customer_id',

                    auth()->id()

                )

                ->orWhere(

                    'vendor_id',

                    auth()->id()
                );
            })

            ->latest()

            ->get();

        return [
                'conversations' =>
                    $conversations
            ];
    }
};
?>

<div>

    <h1 class="text-4xl font-bold mb-10">

        My Chats

    </h1>

    <div class="space-y-5">

        @forelse($conversations as $conversation)

            <div
                class="bg-white p-5 rounded shadow">

                <div
                    class="flex justify-between items-center">

                    <div>

                        <h2
                            class="text-xl font-bold">

                            Product:

                            {{ $conversation->product?->name }}

                        </h2>

                        <p class="text-gray-600">

                            Vendor:

                            {{ $conversation->vendor?->name }}

                        </p>

                    </div>

                    <a
                        href="/products/{{ $conversation->product?->id }}"
                        class="bg-blue-500 text-white px-5 py-2 rounded">

                        Open Chat

                    </a>

                </div>

            </div>

        @empty

            <div
                class="bg-white p-10 rounded shadow text-center">

                No chats found.

            </div>

        @endforelse

    </div>

</div>