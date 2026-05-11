<?php
namespace App\Livewire\Store;

use Livewire\Component;

use App\Models\Product;

use App\Models\Review;

use App\Models\OrderItem;

new class extends Component
{
    public Product $product;

    public $rating = 5;

    public $review = '';

    public function canReview()
    {
        if (! auth()->check()) {

            return false;
        }

        return OrderItem::where(

            'product_id',

            $this->product->id

        )->whereHas(

            'order',

            function ($query) {

                $query->where(

                    'user_id',

                    auth()->id()

                )->where(

                    'payment_status',

                    'paid'
                );
            }

        )->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Submit Review
    |--------------------------------------------------------------------------
    */

    public function submitReview()
    {
        if (! $this->canReview()) {

            session()->flash(

                'error',

                'Only verified buyers can review.'
            );

            return;
        }

        $this->validate([

            'rating' =>
                'required|integer|min:1|max:5',

            'review' =>
                'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(

            [

                'user_id' =>
                    auth()->id(),

                'product_id' =>
                    $this->product->id,
            ],

            [

                'rating' =>
                    $this->rating,

                'review' =>
                    $this->review,

                'is_approved' =>
                    true,
            ]
        );

        session()->flash(

            'success',

            'Review submitted successfully.'
        );

        $this->reset([

            'rating',

            'review'
        ]);

        $this->rating = 5;
    }

    public function with(): array
    {
        $reviews = Review::with('user')

            ->where('product_id', $this->product->id)
            ->where('is_approved', true)
            ->latest()
            ->get();

        return ['reviews' => $reviews];
    }
};
?>

<div class="mt-10">

    <h2 class="text-3xl font-bold mb-5">

        Product Reviews

    </h2>

    {{-- Flash Messages --}}

    @if(session()->has('success'))

        <div
            class="bg-green-100 text-green-700 p-4 rounded mb-5">

            {{ session('success') }}

        </div>

    @endif

    @if(session()->has('error'))

        <div
            class="bg-red-100 text-red-700 p-4 rounded mb-5">

            {{ session('error') }}

        </div>

    @endif

    {{-- Average Rating --}}

    <div class="mb-8">

        <p class="text-2xl font-bold">

            ⭐ {{ $product->averageRating() ?: 0 }}/5

        </p>

    </div>

    {{-- Review Form --}}

    @auth

        @if($this->canReview())

            <div
                class="bg-white p-6 rounded shadow mb-10">

                <h3
                    class="text-2xl font-bold mb-5">

                    Write Review

                </h3>

                <div class="space-y-5">

                    <select
                        wire:model="rating"
                        class="w-full border p-3 rounded">

                        <option value="5">
                            ⭐⭐⭐⭐⭐
                        </option>

                        <option value="4">
                            ⭐⭐⭐⭐
                        </option>

                        <option value="3">
                            ⭐⭐⭐
                        </option>

                        <option value="2">
                            ⭐⭐
                        </option>

                        <option value="1">
                            ⭐
                        </option>

                    </select>

                    <textarea
                        wire:model="review"
                        placeholder="Write your review..."
                        class="w-full border p-3 rounded"
                    ></textarea>

                    <button
                        wire:click="submitReview"
                        class="bg-green-500 text-white px-6 py-3 rounded">

                        Submit Review

                    </button>

                </div>

            </div>

        @else

            <div
                class="bg-yellow-100 text-yellow-700 p-4 rounded mb-5">

                Only verified buyers can review this product.

            </div>

        @endif

    @else

        <div
            class="bg-blue-100 text-blue-700 p-4 rounded mb-5">

            Please login to review this product.

        </div>

    @endauth

    {{-- Reviews List --}}

    <div class="space-y-5">

        @forelse($reviews as $review)

            <div
                class="bg-white p-5 rounded shadow">

                <div
                    class="flex justify-between mb-3">

                    <h4 class="font-bold">

                        {{ $review->user?->name }}

                    </h4>

                    <div>

                        @for($i = 1; $i <= 5; $i++)

                            @if($i <= $review->rating)

                                ⭐

                            @else

                                ☆

                            @endif

                        @endfor

                    </div>

                </div>

                <p class="text-gray-700">

                    {{ $review->review }}

                </p>

            </div>

        @empty

            <div
                class="bg-white p-10 rounded shadow text-center">

                No reviews yet.

            </div>

        @endforelse

    </div>

</div>