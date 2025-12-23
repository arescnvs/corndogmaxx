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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('idPesanan');
            $table->unsignedBigInteger('idPelanggan')->nullable();
            $table->foreign('idPelanggan')->references('idPelanggan')->on('pelanggan')->nullOnDelete();
            $table->unsignedBigInteger('idKeranjang')->nullable();
            $table->foreign('idKeranjang')->references('idKeranjang')->on('keranjang')->nullOnDelete();
            $table->unsignedBigInteger('idKasir')->nullable();
            $table->foreign('idKasir')->references('idKasir')->on('users')->nullOnDelete();
            $table->dateTime('tanggalPesanan')->useCurrent();
            $table->string('statusPesanan')->default('Diproses');
            $table->integer('noAntrean')->nullable();
            $table->string('lokasiGPS')->nullable();
            $table->string('sumberPesanan')->nullable(); // online/offline
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
