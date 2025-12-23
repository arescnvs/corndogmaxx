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
        Schema::create('detailKeranjang', function (Blueprint $table) {
            $table->id('idDetail');
            $table->unsignedBigInteger('idKeranjang');
            $table->foreign('idKeranjang')->references('idKeranjang')->on('keranjang')->cascadeOnDelete();
            $table->unsignedBigInteger('idMenu');
            $table->foreign('idMenu')->references('idMenu')->on('menu')->cascadeOnDelete();
            $table->integer('jumlahItem');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailKeranjang');
    }
};
