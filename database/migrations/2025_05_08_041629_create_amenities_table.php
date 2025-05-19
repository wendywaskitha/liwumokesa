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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
           $table->string('name');
            $table->string('slug')->unique();
            $table->string('type');
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->text('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('featured_image')->nullable();
            $table->string('availability')->default('custom');
            $table->string('opening_hours')->nullable();
            $table->string('closing_hours')->nullable();
            $table->text('operational_notes')->nullable();
            $table->boolean('is_free')->default(true);
            $table->decimal('fee', 10, 2)->nullable();
            $table->boolean('is_accessible')->default(false);
            $table->json('features')->nullable();
            $table->text('description')->nullable();
            $table->string('contact')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
