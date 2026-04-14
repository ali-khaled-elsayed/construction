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
        Schema::create('job_short_listing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['interested', 'shortlisted', 'paid', 'withdraw', 'cancelled', 'accepted']);
            $table->decimal('fee_amount', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('interested_at')->nullable();
            $table->timestamp('shortlisted_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('withdraw_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique(['job_id', 'provider_id']);
            $table->index(['job_id', 'status']);
            $table->index(['provider_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_short_listing');
    }
};
