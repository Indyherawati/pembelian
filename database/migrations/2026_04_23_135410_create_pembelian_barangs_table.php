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
        Schema::create('pembelian_barang', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pembelian_id')->constrained('pembelian')->cascadeOnDelete();
    $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
    $table->integer('jml');
    $table->integer('harga');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_barangs');
    }
};
