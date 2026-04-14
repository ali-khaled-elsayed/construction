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
        Schema::create('job_attribute_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id');
            $table->string('language_code', 10);
            $table->string('name');
            $table->string('hint')->nullable();
            $table->timestamps();

            $table->foreign('attribute_id')->references('id')->on('job_attributes')->cascadeOnDelete();
            $table->foreign('language_code')->references('code')->on('languages')->cascadeOnDelete();
            $table->unique(['attribute_id', 'language_code']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_attribute_translations');
    }
};
