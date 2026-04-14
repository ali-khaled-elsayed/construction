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
        Schema::create('job_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('job_categories')->cascadeOnDelete();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->integer('fee_amount')->default(0);
            $table->enum('size', ['small', 'medium', 'large'])->default('medium');
            $table->text('description')->nullable();
            $table->enum('urgency', ['standard', 'urgent'])->default('standard');
            $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])->default('open');
            $table->timestamps();

            $table->foreign('room_id')->nullOnDelete()->references('id')->on('rooms');
            $table->index('job_request_id');
            $table->index('category_id');
            $table->index('room_id');
            $table->index('status');
            $table->index('urgency');
            $table->index('size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_items');
    }
};
