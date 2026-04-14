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
        Schema::create('room_type_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_type_id');
            $table->string('language_code', 10);
            $table->string('name');
            $table->timestamps();

            $table->foreign('room_type_id')->references('id')->on('room_types')->cascadeOnDelete();
            $table->foreign('language_code')->references('code')->on('languages')->cascadeOnDelete();
            $table->unique(['room_type_id', 'language_code']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_type_translations');
    }
};
