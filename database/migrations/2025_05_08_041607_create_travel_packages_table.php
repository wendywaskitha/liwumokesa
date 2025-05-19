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
        Schema::create('travel_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('highlights')->nullable();
            $table->integer('duration');
            $table->integer('duration_unit')->default(1); // 1=days, 2=nights, 3=hours
            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->text('itinerary')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('meeting_point')->nullable();
            $table->integer('min_participants')->default(1);
            $table->integer('max_participants')->nullable();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->string('featured_image')->nullable();
            $table->enum('difficulty', ['easy', 'moderate', 'challenging'])->default('easy');
            $table->boolean('is_private')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        
        // Tabel pivot untuk relasi many-to-many dengan destinations
        Schema::create('destination_travel_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_package_id')->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->integer('day')->default(1); // Hari ke berapa dalam itinerary
            $table->integer('order')->default(1); // Urutan kunjungan dalam hari itu
            $table->text('notes')->nullable(); // Catatan khusus untuk destinasi ini
            $table->timestamps();
            
            $table->unique(['travel_package_id', 'destination_id', 'day'], 'destination_package_day_unique');
        });
        
        // Tabel pivot untuk relasi many-to-many dengan accommodations
        Schema::create('accommodation_travel_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_package_id')->constrained()->onDelete('cascade');
            $table->foreignId('accommodation_id')->constrained()->onDelete('cascade');
            $table->integer('day')->default(1); // Hari menginap ke berapa
            $table->text('notes')->nullable(); // Catatan tentang penginapan
            $table->timestamps();
            
            $table->unique(['travel_package_id', 'accommodation_id', 'day'], 'accommodation_package_day_unique');
        });
        
        // Tabel pivot untuk relasi many-to-many dengan transportations
        Schema::create('travel_package_transportation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_package_id')->constrained()->onDelete('cascade');
            $table->foreignId('transportation_id')->constrained()->onDelete('cascade');
            $table->text('route_details')->nullable(); // Detail rute 
            $table->text('notes')->nullable(); // Catatan lainnya
            $table->timestamps();
            
            $table->unique(['travel_package_id', 'transportation_id'], 'package_transportation_unique');
        });
        
        // Tabel untuk availability dengan tanggal dan kuota
        Schema::create('travel_package_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_package_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('quota');
            $table->boolean('is_available')->default(true);
            $table->decimal('special_price', 12, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['travel_package_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_package_availabilities');
        Schema::dropIfExists('travel_package_transportation');
        Schema::dropIfExists('accommodation_travel_package');
        Schema::dropIfExists('destination_travel_package');
        Schema::dropIfExists('travel_packages');
    }
};
