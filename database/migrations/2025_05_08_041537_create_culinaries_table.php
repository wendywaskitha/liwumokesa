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
        Schema::create('culinaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            // Ubah definisi kolom type menjadi string yang cukup panjang
            // atau gunakan enum dengan semua nilai yang diperlukan
            $table->enum('type', [
                'seafood',
                'tradisional',
                'kafe',
                'cafe',
                'restoran',
                'warung',
                'street_food',
                'bakery'
            ])->comment('Jenis tempat kuliner');

            $table->text('description');
            $table->string('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->decimal('price_range_start', 12, 2);
            $table->decimal('price_range_end', 12, 2);
            $table->string('opening_hours')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('status')->default(true);

            // Kolom tambahan
            $table->string('social_media')->nullable();
            $table->boolean('has_vegetarian_option')->default(false);
            $table->boolean('halal_certified')->default(true);
            $table->boolean('has_delivery')->default(false);
            $table->json('featured_menu')->nullable();
            $table->boolean('is_recommended')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('culinaries');
    }
};
