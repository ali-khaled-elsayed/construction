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
        Schema::create('job_short_listing_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_listing_id')->constrained('job_short_listing')->cascadeOnDelete();
            $table->enum('old_status', ['interested', 'shortlisted', 'paid', 'withdraw', 'cancelled', 'accepted']);
            $table->enum('new_status', ['interested', 'shortlisted', 'paid', 'withdraw', 'cancelled', 'accepted']);
            $table->foreignId('changed_by')->constrained('users')->cascadeOnDelete();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index('short_listing_id');
            $table->index('changed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_short_listing_history');
    }
};
