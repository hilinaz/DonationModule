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
        Schema::create('donor_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
            $table->boolean('accepts_email')->default(true);
            $table->boolean('accepts_sms')->default(false);
            $table->boolean('newsletter_opt_in')->default(false);
            $table->json('campaign_interests')->nullable();
            $table->string('preferred_language')->default('en');
            $table->timestamps();

            $table->unique('donor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donor_preferences');
    }
};
