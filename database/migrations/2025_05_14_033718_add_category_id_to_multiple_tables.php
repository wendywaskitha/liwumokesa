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
        // Tambahkan category_id ke tabel destinations jika belum ada
        if (Schema::hasTable('destinations') && !Schema::hasColumn('destinations', 'category_id')) {
            Schema::table('destinations', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            });
        }

        // Tambahkan category_id ke tabel culinaries jika belum ada
        if (Schema::hasTable('culinaries') && !Schema::hasColumn('culinaries', 'category_id')) {
            Schema::table('culinaries', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            });
        }

        // Tambahkan category_id ke tabel creative_economies jika belum ada
        if (Schema::hasTable('creative_economies') && !Schema::hasColumn('creative_economies', 'category_id')) {
            Schema::table('creative_economies', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            });
        }

        // Tambahkan category_id ke tabel events jika belum ada
        if (Schema::hasTable('events') && !Schema::hasColumn('events', 'category_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus foreign key constraints dan kolom jika ada
        if (Schema::hasTable('destinations') && Schema::hasColumn('destinations', 'category_id')) {
            Schema::table('destinations', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }

        if (Schema::hasTable('culinaries') && Schema::hasColumn('culinaries', 'category_id')) {
            Schema::table('culinaries', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }

        if (Schema::hasTable('creative_economies') && Schema::hasColumn('creative_economies', 'category_id')) {
            Schema::table('creative_economies', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }

        if (Schema::hasTable('events') && Schema::hasColumn('events', 'category_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }
    }
};
