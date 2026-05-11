<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
             $table->string('shipping_name')
                ->nullable();

            $table->string('shipping_phone')
                ->nullable();

            $table->text('shipping_address')
                ->nullable();

            $table->string('shipping_city')
                ->nullable();

            $table->string('shipping_state')
                ->nullable();

            $table->string('shipping_country')
                ->default('India');

            $table->string('shipping_pincode')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Shipping
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'shipping_charge',
                10,
                2
            )->default(0);

            $table->string('shipping_method')
                ->default('Standard');

            /*
            |--------------------------------------------------------------------------
            | Delivery Status
            |--------------------------------------------------------------------------
            */

            $table->string('delivery_status')
                ->default('Pending');

            $table->timestamp('shipped_at')
                ->nullable();

            $table->timestamp('delivered_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Tracking
            |--------------------------------------------------------------------------
            */

            $table->string('tracking_number')
                ->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([

                'shipping_name',

                'shipping_phone',

                'shipping_address',

                'shipping_city',

                'shipping_state',

                'shipping_country',

                'shipping_pincode',

                'shipping_charge',

                'shipping_method',

                'delivery_status',

                'shipped_at',

                'delivered_at',

                'tracking_number',
            ]);
        });
    }
};
