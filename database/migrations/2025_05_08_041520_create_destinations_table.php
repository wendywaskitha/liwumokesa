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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('type')->nullable(); // Tipe destinasi (beach, historical, nature, etc)
            $table->string('location')->nullable();
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('visiting_hours')->nullable();
            $table->decimal('entrance_fee', 10, 2)->default(0);
            $table->text('facilities')->nullable();
            $table->string('website')->nullable();
            $table->string('contact')->nullable();
            $table->string('best_time_to_visit')->nullable();
            $table->text('tips')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
