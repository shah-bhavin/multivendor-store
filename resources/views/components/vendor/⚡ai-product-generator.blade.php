<?php
namespace App\Livewire\Vendor;

use Livewire\Component;

use App\Services\AIService;

new class extends Component
{
    public $productName = '';

    public $generatedDescription = '';

    public $generatedSeo = '';

     public function generate()
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validate([

            'productName' =>
                'required|min:3',
        ]);

        /*
        |--------------------------------------------------------------------------
        | AI Service
        |--------------------------------------------------------------------------
        */

        $ai = app(AIService::class);

        /*
        |--------------------------------------------------------------------------
        | Generate Description
        |--------------------------------------------------------------------------
        */

        $this->generatedDescription =
            $ai->generateDescription(

                $this->productName
            );

        /*
        |--------------------------------------------------------------------------
        | Generate SEO
        |--------------------------------------------------------------------------
        */

        $this->generatedSeo =
            $ai->generateSeo(

                $this->productName
            );
    }

    public function with(); array
    {
        return;
    }

};
?>

<div class="bg-white p-6 rounded shadow">

    <h2
        class="text-2xl font-bold mb-6">

        AI Product Generator

    </h2>

    {{-- Product Name --}}

    <div class="mb-5">

        <label class="font-bold">

            Product Name

        </label>

        <input
            type="text"
            wire:model="productName"
            class="w-full border rounded p-3 mt-2"
            placeholder="Enter product name"
        >

        @error('productName')

            <div class="text-red-500 mt-2">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- Generate Button --}}

    <button
        wire:click="generate"

        class="
            bg-blue-500
            text-white
            px-6
            py-3
            rounded
        ">

        Generate AI Content

    </button>

    {{-- Description --}}

    @if($generatedDescription)

        <div class="mt-8">

            <h3
                class="font-bold text-lg mb-3">

                AI Description

            </h3>

            <div
                class="bg-gray-100 p-5 rounded">

                {{ $generatedDescription }}

            </div>

        </div>

    @endif

    {{-- SEO --}}

    @if($generatedSeo)

        <div class="mt-8">

            <h3
                class="font-bold text-lg mb-3">

                AI SEO Meta

            </h3>

            <div
                class="bg-gray-100 p-5 rounded">

                {{ $generatedSeo }}

            </div>

        </div>

    @endif

</div>