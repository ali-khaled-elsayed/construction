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
        Schema::create('job_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('job_categories')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_required')->default(false);
            $table->enum('data_type', ['text', 'number', 'boolean', 'select'])->default('text');
            $table->timestamps();

            $table->index('category_id');
            $table->index('is_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_attributes');
    }
};
