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
        Schema::create('city_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('language_code', 10);
            $table->string('name');
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->foreign('language_code')->references('code')->on('languages')->cascadeOnDelete();
            $table->unique(['city_id', 'language_code']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_translations');
    }
};
