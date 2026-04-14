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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_request_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('room_type_id');
            $table->decimal('area', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('room_type_id')->references('id')->on('room_types')->cascadeOnDelete();
            $table->index('job_request_id');
            $table->index('room_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
