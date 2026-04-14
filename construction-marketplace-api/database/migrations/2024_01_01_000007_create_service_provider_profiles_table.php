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
        Schema::create('service_provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
            $table->foreign('country_code')->references('code')->on('countries')->nullOnDelete();
            $table->index('rating');
            $table->index(['city_id', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_provider_profiles');
    }
};
