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
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Tambahkan kolom toko_id dan foreign key baru
            $table->unsignedBigInteger('toko_id')->after('stok');
            $table->foreign('toko_id')->references('id')->on('toko')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');

            // Kembalikan user_id dan foreign key sebelumnya
            $table->unsignedBigInteger('user_id')->after('stok');
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');//
        });
    }
};
