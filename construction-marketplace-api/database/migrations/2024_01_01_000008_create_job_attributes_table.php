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
            $table->foreignId('job_category_id')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->string('type')->default('text'); // text, number, boolean, select, multi_select
            $table->json('options')->nullable(); // for select and multi_select types
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['job_category_id', 'code']);
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
