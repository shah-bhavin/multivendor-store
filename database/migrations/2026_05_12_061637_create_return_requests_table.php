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
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('order_id')
                    ->constrained()
                    ->cascadeOnDelete();

                

            $table->string('type')
                    ->default('refund');

                

            $table->text('reason');

            $table->decimal(
                    'refund_amount',
                    10,
                    2
                )->nullable();

                

            $table->string('status')
                    ->default('Pending');

                

            $table->text('admin_note')
                    ->nullable();

            $table->timestamp('processed_at')
                    ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
