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
            $table->foreignId('short_listing_id')->constrained('job_short_listing')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('old_status', ['interested', 'shortlisted', 'paid', 'withdraw', 'cancelled', 'accepted']);
            $table->enum('new_status', ['interested', 'shortlisted', 'paid', 'withdraw', 'cancelled', 'accepted']);
            $table->text('description')->nullable();
            $table->json('changes')->nullable();
            $table->timestamps();

            $table->index(['short_listing_id', 'created_at']);
            $table->index('user_id');
            $table->index('old_status');
            $table->index('new_status');
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
