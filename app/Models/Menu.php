<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';            // nama tabel di database
    protected $primaryKey = 'idMenu';     // primary key
    public $timestamps = true;            // karena ada created_at dan updated_at

    protected $fillable = [
        'namaMenu',
        'hargaProduk',
    ];

    // Relasi ke tabel stok (1 menu punya 1 stok)
    public function stok()
    {
        return $this->hasOne(Stok::class, 'idMenu', 'idMenu');
    }

    // (Opsional) Bisa juga kamu tambahkan relasi ke Pesanan nanti
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'idMenu', 'idMenu');
    }
}
