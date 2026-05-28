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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->string('donation_type')->default('one_time'); // one_time|recurring|pledge|in_kind
            $table->string('source')->default('offline'); // portal|event|offline
            $table->string('payment_status')->default('pending'); // pending|completed|failed|refunded
            $table->string('currency', 3)->default('USD');
            $table->decimal('amount_original', 16, 2);
            $table->decimal('exchange_rate', 16, 6)->default(1);
            $table->decimal('amount_base', 16, 2);
            $table->string('base_currency', 3)->default('USD');
            $table->boolean('gift_aid_eligible')->default(false);
            $table->string('payment_gateway')->nullable();
            $table->string('transaction_reference')->nullable()->index();
            $table->timestamp('donated_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['payment_status', 'donated_at']);
            $table->index(['currency', 'base_currency']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
