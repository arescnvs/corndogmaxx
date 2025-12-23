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
        Schema::create('itemPesanan', function (Blueprint $table) {
            $table->id('idItem');
            $table->unsignedBigInteger('idPesanan');
            $table->foreign('idPesanan')->references('idPesanan')->on('pesanan')->cascadeOnDelete();
            $table->unsignedBigInteger('idMenu');
            $table->foreign('idMenu')->references('idMenu')->on('menu')->cascadeOnDelete();
            $table->unsignedBigInteger('idTopping')->nullable();
            $table->foreign('idTopping')->references('idTopping')->on('topping')->nullOnDelete();
            $table->integer('jumlahPesanan');
            $table->text('catatanPesanan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemPesanan');
    }
};
