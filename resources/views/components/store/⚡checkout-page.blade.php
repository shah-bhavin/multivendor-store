<?php

namespace App\Livewire\Store;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Notifications\OrderPlacedNotification;

use Razorpay\Api\Api;

new class extends Component
{
    #[Layout('layouts.store')]

    public $customer_phone;

    public $customer_address;

    public $order;

    public $cart = [];

    public $couponCode = '';

    public $discount = 0;

    public $appliedCoupon = null;

    public function mount()
    {
        if (! auth()->check()) {

            return redirect('/login');
        }

        $this->cart = session()
            ->get('cart', []);

        if (auth()->check()) {

            $this->customer_name =
                auth()->user()->name;

            $this->customer_email =
                auth()->user()->email;
        }
    }
    public function getSubtotalProperty()
    {
        return collect($this->cart)
            ->sum(function ($item) {

                return $item['price']
                    * $item['quantity'];
            });
    }

    public function getFinalTotalProperty()
    {
        return max(

            0,

            $this->subtotal
            - $this->discount
        );
    }

    public function applyCoupon(){
    
        $coupon = Coupon::where(

            'code',

            strtoupper($this->couponCode)

        )->first();

        /*
        |--------------------------------------------------------------------------
        | Coupon Exists?
        |--------------------------------------------------------------------------
        */

        if (! $coupon) {

            session()->flash(

                'error',

                'Invalid coupon code.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Active Coupon?
        |--------------------------------------------------------------------------
        */

        if (! $coupon->is_active) {

            session()->flash(

                'error',

                'Coupon is inactive.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Expiry Check
        |--------------------------------------------------------------------------
        */

        if (

            $coupon->expiry_date

            &&

            now()->gt($coupon->expiry_date)

        ) {

            session()->flash(

                'error',

                'Coupon has expired.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Usage Limit
        |--------------------------------------------------------------------------
        */

        if (

            $coupon->usage_limit

            &&

            $coupon->used_count >=
            $coupon->usage_limit

        ) {

            session()->flash(

                'error',

                'Coupon usage limit reached.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | APPLY DISCOUNT
        |--------------------------------------------------------------------------
        */

        if ($coupon->type === 'fixed') {

            $this->discount =
                $coupon->value;

        } else {

            $this->discount =
                ($this->subtotal
                * $coupon->value) / 100;
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Negative Total
        |--------------------------------------------------------------------------
        */

        if ($this->discount > $this->subtotal) {

            $this->discount =
                $this->subtotal;
        }

        $this->appliedCoupon = $coupon;
        session([
            'coupon_id' =>
                $coupon->id
        ]);
        session()->flash(

            'success',

            'Coupon applied successfully.'
        );
    }

    public function placeOrder()
    {
        $this->validate([

            'customer_phone' =>
                'required',

            'customer_address' =>
                'required',
        ]);

        auth()->user()->update([

            'phone' =>
                $this->customer_phone,

            'address' =>
                $this->customer_address,
        ]);

        // $total = collect($this->cart)
        //     ->sum(function ($item) {

        //     return $item['price']
        //         * $item['quantity'];
        // });

        $order = Order::create([

            'user_id' =>
                auth()->id(),

            'customer_name' =>
                auth()->user()->name,

            'customer_email' =>
                auth()->user()->email,

            'customer_phone' =>
                $this->customer_phone,

            'customer_address' =>
                $this->customer_address,

            'total_amount' =>
                $this->finalTotal,

            'status' =>
                'pending',

            'payment_status' =>
                'pending',

            'user_id' =>
                auth()->id(),
        ]);



        foreach ($this->cart as $item) {

            OrderItem::create([

                'order_id' =>
                    $order->id,

                'product_id' =>
                    $item['id'],

                'quantity' =>
                    $item['quantity'],

                'price' =>
                    $item['price'],
            ]);

            $product = Product::find(
                $item['product_id']
            );

            if ($product) {

                $product->reduceStock(

                    $item['quantity']
                );
            }

            // $product = \App\Models\Product::find(
            //     $item['id']
            // );

            // if ($product && $product->user) {

            //     $product->user->notify(

            //         new OrderPlacedNotification(
            //             $order
            //         )
            //     );
            // }
        }

        $api = new Api(
            env('RAZORPAY_KEY'),
            env('RAZORPAY_SECRET')
        );

        $razorpayOrder = $api->order->create([

            'receipt' =>
                'order_' . $order->id,

            'amount' =>
                $this->finalTotal * 100,

            'currency' =>
                'INR',
        ]);


        // $admin = User::role('admin')
        //     ->first();

        // if ($admin) {

        //     $admin->notify(

        //         new OrderPlacedNotification(
        //             $order
        //         )
        //     );
        // }
        $order->update([

            'payment_id' =>
                $razorpayOrder['id'],
        ]);

        session([
            'razorpay_order_id' =>
                $razorpayOrder['id']
        ]);

        return redirect()->route(
            'payment.page',
            $order->id
        );

        
    }
};
?>

<div>

    <h1 class="text-4xl font-bold mb-10">

        Checkout

    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        {{-- Checkout Form --}}

        <div class="bg-white p-6 rounded shadow">

            <h2 class="text-2xl font-bold mb-5">

                Customer Information

            </h2>

            {{-- Logged User Info --}}

            <div class="mb-5">

                <p class="mb-2">

                    <strong>Name:</strong>

                    {{ auth()->user()->name }}

                </p>

                <p>

                    <strong>Email:</strong>

                    {{ auth()->user()->email }}

                </p>

            </div>

            <div class="space-y-5">

                <input
                    type="text"
                    wire:model="customer_phone"
                    placeholder="Phone Number"
                    class="w-full border p-3 rounded"
                >

                @error('customer_phone')

                    <p class="text-red-500 text-sm">

                        {{ $message }}

                    </p>

                @enderror

                <textarea
                    wire:model="customer_address"
                    placeholder="Delivery Address"
                    class="w-full border p-3 rounded"
                ></textarea>

                @error('customer_address')

                    <p class="text-red-500 text-sm">

                        {{ $message }}

                    </p>

                @enderror

                <button
                    wire:click="placeOrder"
                    class="bg-green-500 text-white px-6 py-3 rounded">

                    Proceed To Payment

                </button>

            </div>

        </div>

        {{-- Order Summary --}}

        <div class="bg-white p-6 rounded shadow">

            <h2 class="text-2xl font-bold mb-5">

                Order Summary

            </h2>

            @foreach($cart as $item)

                <div
                    class="flex justify-between border-b py-3">

                    <div>

                        {{ $item['name'] }}
                        ×
                        {{ $item['quantity'] }}

                    </div>

                    <div>

                        ₹{{ $item['price'] * $item['quantity'] }}

                    </div>

                </div>

            @endforeach

            <div class="mt-5">

                <div class="flex justify-between mb-2">

                    <span>Subtotal</span>

                    <span>

                        ₹{{ $this->subtotal }}

                    </span>

                </div>

                <div class="flex justify-between mb-2">

                    <span>Discount</span>
                    @if($appliedCoupon)

                        ({{ $appliedCoupon->code }})

                    @endif

                    <span>

                        ₹{{ $discount }}

                    </span>

                </div>
                {{-- Coupon Section --}}

                <div class="mt-5">

                    <h3 class="text-xl font-bold mb-3">

                        Apply Coupon

                    </h3>

                    <div class="flex gap-2">

                        <input
                            type="text"
                            wire:model="couponCode"
                            placeholder="Enter coupon code"
                            class="border p-3 rounded w-full"
                        >

                        <button
                            wire:click="applyCoupon"
                            class="bg-blue-500 text-white px-5 rounded">

                            Apply

                        </button>

                    </div>

                    {{-- Success Message --}}

                    @if(session()->has('success'))

                        <div
                            class="bg-green-100 text-green-700 p-3 rounded mt-3">

                            {{ session('success') }}

                        </div>

                    @endif

                    {{-- Error Message --}}

                    @if(session()->has('error'))

                        <div
                            class="bg-red-100 text-red-700 p-3 rounded mt-3">

                            {{ session('error') }}

                        </div>

                    @endif

                </div>
                <div
                    class="flex justify-between text-2xl font-bold">

                    <span>Total</span>

                    <span>

                        ₹{{ $this->finalTotal }}

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>