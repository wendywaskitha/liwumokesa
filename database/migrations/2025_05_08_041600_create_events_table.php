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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location');
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->string('organizer')->nullable();
            $table->string('contact_person')->nullable(); // Tambahkan kolom ini
            $table->string('contact_phone')->nullable(); // Tambahkan kolom ini
            $table->boolean('is_free')->default(false);
            $table->decimal('ticket_price', 10, 2)->default(0);
            $table->integer('capacity')->nullable();
            $table->text('schedule_info')->nullable(); // Tambahkan kolom ini
            $table->text('facilities')->nullable(); // Tambahkan kolom ini
            $table->boolean('is_recurring')->default(false); // Tambahkan kolom ini
            $table->string('recurring_type')->nullable(); // Tambahkan kolom ini
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
        // Schema::dropIfExists('cultural_heritage_event');
        Schema::dropIfExists('events');
    }
};
