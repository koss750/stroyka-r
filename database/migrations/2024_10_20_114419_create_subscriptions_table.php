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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_plan_id')->constrained('price_plans');
            $table->string('entity_type'); //e.g. supplier, user, etc
            $table->foreignId('entity_id'); // id of supplier, user, etc
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_reference')->nullable();
            $table->json('parameters')->nullable();
            $table->timestamp('last_payment_at')->nullable();
            $table->timestamp('next_payment_due')->nullable();
            $table->boolean('is_cancelled')->default(false);
            $table->boolean('is_paused')->default(false);
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};