<?php
namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Razorpay\Api\Api;

new class extends Component
{
    #[Layout('layouts.store')]
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order;
    }
};
?>

<div class="max-w-2xl mx-auto">

    <div class="bg-white p-10 rounded shadow">

        <h1 class="text-3xl font-bold mb-5">

            Complete Payment

        </h1>

        <p class="mb-5">

            Order Total:
            ₹{{ $order->total_amount }}

        </p>

        <button
            id="pay-btn"
            class="bg-green-500 text-white px-6 py-3">

            Pay Now

        </button>

    </div>
</div>


@script
<script>
    document
        .getElementById('pay-btn')
        .onclick = function () {
            
        var options = {

            "key":
                "{{ env('RAZORPAY_KEY') }}",

            "amount":
                "{{ $order->total_amount * 100 }}",

            "currency":
                "INR",

            "name":
                "Multi Vendor Store",

            "description":
                "Order Payment",

            "order_id":
                "{{ $order->payment_id }}",

            "handler": function (response) {

                window.location.href =
                    "/payment-success/{{ $order->id }}?payment_id="
                    + response.razorpay_payment_id;
            }
        };
        
        var rzp1 =
            new Razorpay(options);

        rzp1.open();
    };

</script>
@endscript
