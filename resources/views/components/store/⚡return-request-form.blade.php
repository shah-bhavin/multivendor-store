<?php
namespace App\Livewire\Store;

use Livewire\Component;

use App\Models\Order;

use App\Models\ReturnRequest;

new class extends Component
{
    public $order;

    public $type = 'refund';

    public $reason = '';

    public function mount($orderId)
    {
        $this->order = Order::findOrFail(
            $orderId
        );
    }

    public function submit()
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validate([

            'type' =>
                'required|string',

            'reason' =>
                'required|min:10',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Pending Requests
        |--------------------------------------------------------------------------
        */

        $alreadyExists =
            ReturnRequest::where(

                'order_id',

                $this->order->id
            )

            ->where(
                'status',
                'Pending'
            )

            ->exists();

        if ($alreadyExists) {

            session()->flash(

                'error',

                'Pending request already exists.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Request
        |--------------------------------------------------------------------------
        */

        ReturnRequest::create([

            'user_id' => auth()->id(),

            'order_id' => $this->order->id,

            'type' => $this->type,

            'reason' => $this->reason,

            'refund_amount' =>
                $this->order->total_amount,

            'status' => 'Pending',
        ]);

        session()->flash(

            'success',

            'Return request submitted successfully.'
        );

        /*
        |--------------------------------------------------------------------------
        | Reset
        |--------------------------------------------------------------------------
        */

        $this->reset([

            'type',

            'reason',
        ]);

        $this->type = 'refund';
    }

};
?>

<div class="bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-6">

        Return / Refund Request

    </h2>

    {{-- Success Message --}}

    @if(session()->has('success'))

        <div
            class="bg-green-100 text-green-700 p-4 rounded mb-4">

            {{ session('success') }}

        </div>

    @endif

    {{-- Error Message --}}

    @if(session()->has('error'))

        <div
            class="bg-red-100 text-red-700 p-4 rounded mb-4">

            {{ session('error') }}

        </div>

    @endif

    {{-- Type --}}

    <div class="mb-5">

        <label class="font-bold">

            Request Type

        </label>

        <select
            wire:model="type"
            class="w-full border rounded p-3 mt-2">

            <option value="refund">

                Refund

            </option>

            <option value="return">

                Return

            </option>

            <option value="replacement">

                Replacement

            </option>

            <option value="cancellation">

                Cancellation

            </option>

        </select>

    </div>

    {{-- Reason --}}

    <div class="mb-5">

        <label class="font-bold">

            Reason

        </label>

        <textarea
            wire:model="reason"
            class="w-full border rounded p-3 mt-2"
            rows="5"
            placeholder="Explain your issue..."
        ></textarea>

        @error('reason')

            <div class="text-red-500 mt-2">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- Submit Button --}}

    <button
        wire:click="submit"
        class="bg-red-500 text-white px-6 py-3 rounded">

        Submit Request

    </button>

</div>