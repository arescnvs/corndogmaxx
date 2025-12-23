<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'idPesanan';
    protected $fillable = [
        'idPelanggan',
        'idKeranjang',
        'idKasir',
        'tanggalPesanan',
        'statusPesanan',
        'noAntrean',
        'lokasiGPS',
        'sumberPesanan',
        'totalPembayaran',
    ];

    public function items()
    {
        return $this->hasMany(ItemPesanan::class, 'idPesanan', 'idPesanan');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'idPelanggan', 'idPelanggan');
    }

    // Hitung total semua item pesanan
    public function getTotalHargaAttribute()
    {
        return $this->items->sum(fn($item) => $item->total_harga);
    }

    public function pembayaran()
    {
    return $this->hasOne(Pembayaran::class, 'idPesanan', 'idPesanan');
    }
}
