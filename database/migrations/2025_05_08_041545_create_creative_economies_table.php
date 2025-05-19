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
        Schema::create('creative_economies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained();
            $table->text('description');
            $table->string('short_description', 300)->nullable();
            $table->string('address');
            $table->foreignId('district_id')->constrained();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('social_media')->nullable();
            $table->string('business_hours')->nullable();
            $table->string('owner_name')->nullable();
            $table->integer('establishment_year')->nullable();
            $table->integer('employees_count')->nullable();
            $table->text('products_description')->nullable();
            $table->decimal('price_range_start', 12, 2);
            $table->decimal('price_range_end', 12, 2);
            $table->boolean('has_workshop')->default(false);
            $table->text('workshop_information')->nullable();
            $table->boolean('has_direct_selling')->default(true);
            $table->string('featured_image')->nullable();
            $table->boolean('status')->default(true);

            // Kolom tambahan
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('accepts_credit_card')->default(false);
            $table->boolean('provides_training')->default(false);
            $table->boolean('shipping_available')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creative_economies');
    }
};
