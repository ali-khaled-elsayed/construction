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
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->string('unit_type')->nullable();
            $table->enum('job_type', ['full', 'partial']);
            $table->enum('service_type', ['specialist', 'service_provider']);
            $table->enum('description_type', ['basic', 'detailed']);
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
            $table->foreign('country_code')->references('code')->on('countries')->nullOnDelete();
            $table->index('customer_id');
            $table->index(['city_id', 'country_code']);
            $table->index('job_type');
            $table->index('service_type');
            $table->index('description_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_requests');
    }
};
