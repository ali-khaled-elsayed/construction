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
        Schema::create('job_category_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_category_id');
            $table->string('language_code', 10);
            $table->string('name');
            $table->string('hint')->nullable();
            $table->timestamps();

            $table->foreign('job_category_id')->references('id')->on('job_categories')->cascadeOnDelete();
            $table->foreign('language_code')->references('code')->on('languages')->cascadeOnDelete();
            $table->unique(['job_category_id', 'language_code']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_category_translations');
    }
};
