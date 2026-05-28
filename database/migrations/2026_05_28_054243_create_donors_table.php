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
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('organization_name')->nullable();
            $table->string('donor_type'); // individual|corporate|foundation
            $table->string('category')->default('regular'); // regular|recurring|vip|major
            $table->string('lifecycle_stage')->default('new'); // new|active|inactive
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('preferred_channel')->nullable(); // email|sms|both
            $table->text('address')->nullable();
            $table->json('interests')->nullable();
            $table->unsignedSmallInteger('engagement_score')->default(0);
            $table->timestamp('last_engaged_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['donor_type', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
