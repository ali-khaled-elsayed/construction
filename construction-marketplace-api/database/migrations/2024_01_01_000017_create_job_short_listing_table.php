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
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['interested', 'shortlisted', 'paid', 'withdraw', 'cancelled', 'accepted'])->default('interested');
            $table->timestamps();

            $table->index('job_id');
            $table->index('provider_id');
            $table->index('status');
            $table->unique(['job_id', 'provider_id']);
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
