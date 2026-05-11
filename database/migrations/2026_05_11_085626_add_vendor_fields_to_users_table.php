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
        Schema::table('users', function (Blueprint $table) {
            $table->string('store_name')
                ->nullable();

            $table->text('store_description')
                ->nullable();

            $table->string('store_slug')
                ->nullable()
                ->unique();

            $table->string('store_logo')
                ->nullable();

            $table->string('store_banner')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([

                'store_name',

                'store_description',

                'store_slug',

                'store_logo',

                'store_banner',
            ]);
        });
    }
};
