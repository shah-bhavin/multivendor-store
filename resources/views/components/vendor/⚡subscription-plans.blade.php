<?php

namespace App\Livewire\Vendor;

use Livewire\Component;

use App\Models\SubscriptionPlan;

use App\Models\VendorSubscription;

class SubscriptionPlans
    extends Component
{
    public $plans = [];

    public function mount()
    {
        $this->plans =
            SubscriptionPlan::where(

                'is_active',

                true
            )->get();
    }

    public function subscribe($planId)
    {
        $plan = SubscriptionPlan::findOrFail(
            $planId
        );

        VendorSubscription::where(

            'user_id',

            auth()->id()

        )->update([

            'status' => 'expired',
        ]);

    
        VendorSubscription::create([

            'user_id' => auth()->id(),

            'subscription_plan_id' =>
                $plan->id,

            'starts_at' => now(),

            'expires_at' => now()
                ->addMonth(),

            'status' => 'active',
        ]);

        session()->flash(

            'success',

            'Subscription activated successfully.'
        );
    }

    public function render()
    {
        return view(

            'livewire.vendor.subscription-plans'
        );
    }
}
?>

<div>

    <h1
        class="
            text-3xl
            font-bold
            mb-10
        ">

        Subscription Plans

    </h1>

    {{-- Success Message --}}

    @if(session()->has('success'))

        <div
            class="
                bg-green-100
                text-green-700
                p-4
                rounded
                mb-5
            ">

            {{ session('success') }}

        </div>

    @endif

    <div
        class="
            grid
            grid-cols-1
            md:grid-cols-3
            gap-6
        ">

        @foreach($plans as $plan)

            <div
                class="
                    border
                    rounded-2xl
                    p-6
                    shadow
                ">

                <h2
                    class="
                        text-2xl
                        font-bold
                        mb-3
                    ">

                    {{ $plan->name }}

                </h2>

                <div
                    class="
                        text-4xl
                        font-bold
                        mb-5
                    ">

                    ₹{{ $plan->price }}

                    <span
                        class="text-sm">

                        /
                        {{ $plan->billing_cycle }}

                    </span>

                </div>

                <ul
                    class="space-y-3 mb-6">

                    <li>

                        Product Limit:
                        {{ $plan->product_limit }}

                    </li>

                    <li>

                        Featured Products:
                        {{ $plan->featured_products ? 'Yes' : 'No' }}

                    </li>

                    <li>

                        Analytics Access:
                        {{ $plan->analytics_access ? 'Yes' : 'No' }}

                    </li>

                </ul>

                <button
                    wire:click="subscribe({{ $plan->id }})"

                    class="
                        bg-blue-500
                        text-white
                        px-5
                        py-3
                        rounded
                        w-full
                    ">

                    Subscribe

                </button>

            </div>

        @endforeach

    </div>

</div>