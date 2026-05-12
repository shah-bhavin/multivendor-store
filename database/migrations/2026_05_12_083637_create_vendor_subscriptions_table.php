<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'vendor_subscriptions',

            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Relationships
                |--------------------------------------------------------------------------
                */

                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId(
                    'subscription_plan_id'
                )
                ->constrained()
                ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Subscription Dates
                |--------------------------------------------------------------------------
                */

                $table->timestamp('starts_at');

                $table->timestamp('expires_at');

                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                $table->string('status')
                    ->default('active');

                /*
                |--------------------------------------------------------------------------
                | active
                | expired
                | cancelled
                |--------------------------------------------------------------------------
                */

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'vendor_subscriptions'
        );
    }
};