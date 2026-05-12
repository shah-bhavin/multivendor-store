<?php
namespace App\Livewire\Store;

use Livewire\Component;

use App\Models\NewsletterSubscriber;

use Illuminate\Support\Facades\Mail;

use App\Mail\WelcomeNewsletterMail;

new class extends Component
{
    public $email = '';

    /*
    |--------------------------------------------------------------------------
    | Subscribe
    |--------------------------------------------------------------------------
    */

    public function subscribe()
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validate([

            'email' => [

                'required',

                'email',

                'unique:newsletter_subscribers,email',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Save Subscriber
        |--------------------------------------------------------------------------
        */

        NewsletterSubscriber::create([

            'email' => $this->email,

            'subscribed_at' => now(),

            Mail::to($this->email)

            ->send(

                new WelcomeNewsletterMail()
            )
        ]);

        /*
        |--------------------------------------------------------------------------
        | Success Message
        |--------------------------------------------------------------------------
        */

        session()->flash(

            'success',

            'Subscribed successfully!'
        );

        /*
        |--------------------------------------------------------------------------
        | Reset Input
        |--------------------------------------------------------------------------
        */

        $this->reset('email');
    }

    public function mount()
    {
        return;
    }
};
?>

<div
    class="bg-gray-900 text-white rounded-2xl p-10 mt-20">

    <div
        class="max-w-3xl mx-auto text-center">

        <h2
            class="text-4xl font-bold mb-4">

            Subscribe To Our Newsletter

        </h2>

        <p
            class="text-gray-300 mb-8">

            Get latest offers, discounts,
            new arrivals, and marketplace updates.

        </p>

        {{-- Success Message --}}

        @if(session()->has('success'))

            <div
                class="bg-green-500 text-white p-4 rounded mb-5">

                {{ session('success') }}

            </div>

        @endif

        {{-- Form --}}

        <div
            class="flex flex-col md:flex-row gap-4">

            <input
                type="email"
                wire:model="email"
                placeholder="Enter your email"
                class="w-full p-4 rounded text-black"
            >

            <button
                wire:click="subscribe"
                class="bg-blue-500 px-8 py-4 rounded font-bold">

                Subscribe

            </button>

        </div>

        {{-- Validation Error --}}

        @error('email')

            <div
                class="text-red-400 mt-3">

                {{ $message }}

            </div>

        @enderror

    </div>

</div>