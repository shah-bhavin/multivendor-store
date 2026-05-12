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
        Schema::create('subscription_plans', function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Plan Info
                |--------------------------------------------------------------------------
                */

                $table->string('name');

                $table->string('slug')
                    ->unique();

                $table->text('description')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Pricing
                |--------------------------------------------------------------------------
                */

                $table->decimal(
                    'price',
                    10,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Billing Cycle
                |--------------------------------------------------------------------------
                */

                $table->string('billing_cycle')
                    ->default('monthly');

                /*
                |--------------------------------------------------------------------------
                | monthly
                | yearly
                |--------------------------------------------------------------------------
                */

                /*
                |--------------------------------------------------------------------------
                | Feature Limits
                |--------------------------------------------------------------------------
                */

                $table->integer('product_limit')
                    ->default(10);

                $table->boolean('featured_products')
                    ->default(false);

                $table->boolean('priority_support')
                    ->default(false);

                $table->boolean('analytics_access')
                    ->default(false);

                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                $table->boolean('is_active')
                    ->default(true);

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
