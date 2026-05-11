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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')
                ->default(0)
                ->change();

            $table->integer('low_stock_alert')
                ->default(5);

            $table->boolean('track_inventory')
                ->default(true);

            $table->boolean('in_stock')
                ->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn([

                'low_stock_alert',

                'track_inventory',

                'in_stock',
            ]);
        });
    }
};
