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
        Schema::create('transportations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['darat', 'laut', 'udara']);
            $table->string('subtype');
            $table->text('description');
            $table->integer('capacity');
            $table->string('price_scheme');
            $table->decimal('base_price', 10, 2);
            $table->string('contact_person')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->json('routes')->nullable();
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
        Schema::dropIfExists('transportations');
    }
};
