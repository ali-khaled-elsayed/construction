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
        Schema::create('basic_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_request_id')->constrained()->cascadeOnDelete();
            $table->integer('rooms_count')->default(0);
            $table->integer('wet_rooms_count')->default(0);
            $table->integer('external_rooms_count')->default(0);
            $table->boolean('has_garden')->default(false);
            $table->boolean('has_roof')->default(false);
            $table->decimal('area', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('job_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_descriptions');
    }
};
