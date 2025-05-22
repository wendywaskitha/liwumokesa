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
        Schema::create('amenity_cultural_heritage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_heritage_id')->constrained()->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->string('opening_hours')->nullable();
            $table->string('closing_hours')->nullable();
            $table->boolean('is_free')->default(true);
            $table->decimal('fee', 10, 2)->nullable();
            $table->boolean('is_accessible')->default(true);
            $table->text('operational_notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_cultural_heritage');
    }
};
