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
        Schema::table('districts', function (Blueprint $table) {
            $table->decimal('area', 10, 2)->nullable()->after('description');
            $table->integer('population')->nullable()->after('area');
            $table->string('postal_code', 10)->nullable()->after('population');
            $table->string('map_image')->nullable()->after('featured_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->dropColumn(['area', 'population', 'postal_code', 'map_image']);
        });
    }
};
