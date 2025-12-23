<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'idPembayaran';
    protected $fillable = [
        'idPesanan',
        'metodePembayaran',
        'statusPembayaran',
        'buktiPembayaran',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'idPesanan', 'idPesanan');
    }

    public function getBuktiUrlAttribute()
    {
    return $this->buktiPembayaran
        ? asset('storage/' . $this->buktiPembayaran)
        : null;
    }
}
