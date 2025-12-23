<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    use HasFactory;

    protected $table = 'itemPesanan';
    protected $primaryKey = 'idItem';
    protected $fillable = [
        'idPesanan',
        'idMenu',
        'idTopping',
        'jumlahPesanan',
        'catatanPesanan',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'idPesanan', 'idPesanan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'idMenu', 'idMenu');
    }

    public function topping()
    {
        return $this->belongsTo(Topping::class, 'idTopping', 'idTopping');
    }

    // Total harga per item
    public function getTotalHargaAttribute()
    {
        $hargaMenu = $this->menu->hargaProduk ?? 0;
        $hargaTopping = $this->topping->hargaTopping ?? 0;
        return ($hargaMenu + $hargaTopping) * $this->jumlahPesanan;
    }
}
