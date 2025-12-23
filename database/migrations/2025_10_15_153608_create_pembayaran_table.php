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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('idPembayaran');
            $table->unsignedBigInteger('idPesanan');
            $table->foreign('idPesanan')->references('idPesanan')->on('pesanan')->cascadeOnDelete();
            $table->string('metodePembayaran');
            $table->string('statusPembayaran')->default('Belum Dibayar');
            $table->string('buktiPembayaran')->nullable(); // file upload
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
