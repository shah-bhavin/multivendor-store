<?php

use Livewire\Component;

use App\Models\Product;
use App\Models\Conversation;
use App\Models\Message;

new class extends Component
{
    public Product $product;

    public $message = '';

    public $conversation;

    public function mount(Product $product)
    {
        $this->product = $product;

        if (auth()->check()) {

            $this->conversation =
                Conversation::firstOrCreate(

                    [

                        'customer_id' =>
                            auth()->id(),

                        'vendor_id' =>
                            $product->user_id,

                        'product_id' =>
                            $product->id,
                    ]
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Send Message
    |--------------------------------------------------------------------------
    */

    public function sendMessage()
    {
        if (! auth()->check()) {

            return redirect('/login');
        }

        $this->validate([

            'message' =>
                'required|string|max:1000',
        ]);

        Message::create([

            'conversation_id' =>
                $this->conversation->id,

            'sender_id' =>
                auth()->id(),

            'message' =>
                $this->message,
        ]);

        $this->reset('message');
    }

    public function with(): array
    {
        $messages = [];

        if ($this->conversation) {

            $messages = Message::with('sender')

                ->where(

                    'conversation_id',

                    $this->conversation->id

                )

                ->latest()

                ->get();
        }

        return [
                'messages' =>
                    $messages
            ];
    }
};
?>

<div class="mt-10">

    <div
        class="bg-white rounded shadow p-6">

        <h2 class="text-2xl font-bold mb-5">

            Chat With Vendor

        </h2>

        {{-- Login Required --}}

        @guest

            <div
                class="bg-yellow-100 text-yellow-700 p-4 rounded">

                Please login to chat with vendor.

            </div>

        @else

            {{-- Messages --}}

            <div
                class="h-96 overflow-y-auto border rounded p-4 mb-5">

                @forelse($messages as $msg)

                    <div
                        class="mb-4">

                        <div
                            class="
                                p-3
                                rounded
                                max-w-lg

                                @if(
                                    $msg->sender_id
                                    === auth()->id()
                                )

                                    bg-blue-500
                                    text-white
                                    ml-auto

                                @else

                                    bg-gray-200

                                @endif
                            ">

                            <div
                                class="text-sm font-bold mb-1">

                                {{ $msg->sender?->name }}

                            </div>

                            <div>

                                {{ $msg->message }}

                            </div>

                        </div>

                    </div>

                @empty

                    <div
                        class="text-center text-gray-500">

                        No messages yet.

                    </div>

                @endforelse

            </div>

            {{-- Send Message --}}

            <div class="flex gap-3">

                <input
                    type="text"
                    wire:model="message"
                    placeholder="Type message..."
                    class="w-full border p-3 rounded"
                >

                <button
                    wire:click="sendMessage"
                    class="bg-green-500 text-white px-6 rounded">

                    Send

                </button>

            </div>

            @error('message')

                <p class="text-red-500 mt-2">

                    {{ $message }}

                </p>

            @enderror

        @endguest

    </div>

</div>