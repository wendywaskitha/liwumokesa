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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            // Composite unique index untuk group dan key
            $table->unique(['group', 'key']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
