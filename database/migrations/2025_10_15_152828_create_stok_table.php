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
        Schema::create('stok', function (Blueprint $table) {
            $table->id('idStok');
            $table->unsignedBigInteger('idMenu');
            $table->foreign('idMenu')->references('idMenu')->on('menu')->cascadeOnDelete();
            $table->unsignedBigInteger('idKasir');
            $table->foreign('idKasir')->references('idKasir')->on('users')->cascadeOnDelete();
            $table->integer('jumlahStok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};
