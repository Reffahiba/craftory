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
        Schema::create('item_pesanan', function (Blueprint $table) {
            $table->increments('id_item_pesanan');
            $table->integer('harga');
            $table->integer('kuantitas');
            $table->integer('sub_total');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_pesanan');
            $table->timestamps();

            $table->foreign('id_produk')->references('id_produk')->on('produk');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pesanan');
    }
};
