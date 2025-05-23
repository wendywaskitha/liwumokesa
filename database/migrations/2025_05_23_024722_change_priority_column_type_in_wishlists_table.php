<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            // Ubah tipe kolom priority dari string ke integer
            $table->integer('priority')->default(1)->change();

            // Update nilai yang ada
            DB::table('wishlists')
                ->where('priority', 'low')
                ->update(['priority' => 1]);

            DB::table('wishlists')
                ->where('priority', 'normal')
                ->update(['priority' => 3]);

            DB::table('wishlists')
                ->where('priority', 'high')
                ->update(['priority' => 5]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            // Ubah kembali tipe kolom priority dari integer ke string
            $table->string('priority')->default('normal')->change();

            // Update nilai kembali ke string
            DB::table('wishlists')
                ->where('priority', 1)
                ->update(['priority' => 'low']);

            DB::table('wishlists')
                ->where('priority', 3)
                ->update(['priority' => 'normal']);

            DB::table('wishlists')
                ->where('priority', 5)
                ->update(['priority' => 'high']);
        });
    }
};
