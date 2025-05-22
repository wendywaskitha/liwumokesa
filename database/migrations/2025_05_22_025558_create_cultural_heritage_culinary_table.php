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
        Schema::create('cultural_heritage_culinary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_heritage_id')->constrained()->cascadeOnDelete();
            $table->foreignId('culinary_id')->constrained('culinaries')->cascadeOnDelete();
            $table->string('service_type')->default('regular');
            $table->boolean('is_recommended')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultural_heritage_culinary');
    }
};
