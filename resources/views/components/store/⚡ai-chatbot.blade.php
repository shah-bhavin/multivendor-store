<?php
namespace App\Livewire\Store;

use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

new class extends Component
{
    public $message = '';

    public $reply = '';

    public function ask()
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validate([

            'message' =>
                'required|min:2',
        ]);

        /*
        |--------------------------------------------------------------------------
        | AI Response
        |--------------------------------------------------------------------------
        */

        $response = OpenAI::chat()->create([

            'model' => 'gpt-4.1-mini',

            'messages' => [

                [
                    'role' => 'system',

                    'content' =>

                        'You are helpful marketplace customer support assistant.',
                ],

                [
                    'role' => 'user',

                    'content' =>
                        $this->message,
                ],
            ],
        ]);

        $this->reply =

            $response
                ->choices[0]
                ->message
                ->content;
    }

    public function mount()
    {
        return;
    }
};
?>

<div class="bg-white p-6 rounded shadow">

    <h2
        class="text-2xl font-bold mb-6">

        AI Customer Support

    </h2>

    {{-- Input --}}

    <textarea
        wire:model="message"
        rows="4"
        class="w-full border rounded p-3"
        placeholder="Ask anything..."
    ></textarea>

    @error('message')

        <div class="text-red-500 mt-2">

            {{ $message }}

        </div>

    @enderror

    {{-- Button --}}

    <button
        wire:click="ask"

        class="
            bg-green-500
            text-white
            px-6
            py-3
            rounded
            mt-4
        ">

        Ask AI

    </button>

    {{-- Reply --}}

    @if($reply)

        <div
            class="
                mt-8
                bg-gray-100
                p-5
                rounded
            ">

            {{ $reply }}

        </div>

    @endif

</div>