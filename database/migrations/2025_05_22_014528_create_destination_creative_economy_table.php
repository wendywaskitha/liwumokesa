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
        Schema::create('destination_creative_economy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('creative_economy_id')->constrained()->cascadeOnDelete();
            $table->string('partnership_type')->default('display');
            $table->boolean('is_featured')->default(false);
            $table->text('notes')->nullable();
            $table->string('workshop_schedule')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_creative_economy');
    }
};
