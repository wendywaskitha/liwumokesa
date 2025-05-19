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
        Schema::table('travel_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_packages', 'type')) {
                $table->string('type')->default('private')->after('difficulty');
            }
            if (!Schema::hasColumn('travel_packages', 'start_date')) {
                $table->date('start_date')->nullable()->after('type');
            }
            if (!Schema::hasColumn('travel_packages', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_packages', function (Blueprint $table) {
            $table->dropColumn(['type', 'start_date', 'end_date']);
        });
    }
};
