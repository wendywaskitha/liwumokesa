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
        Schema::create('cultural_heritages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['tangible', 'intangible']);
            $table->text('description');
            $table->text('historical_significance')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('conservation_status', [
                'excellent',
                'good',
                'fair',
                'poor',
                'critical',
                'unknown'
            ])->nullable();
            $table->enum('recognition_status', [
                'local',
                'regional',
                'national',
                'international',
                'unesco'
            ])->nullable();
            $table->date('recognition_date')->nullable();
            $table->text('practices_description')->nullable();
            $table->text('physical_description')->nullable();
            $table->string('custodian')->nullable();
            $table->text('visitor_info')->nullable();
            $table->boolean('is_endangered')->default(false);
            $table->boolean('allows_visits')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('featured_image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultural_heritages');
    }
};
