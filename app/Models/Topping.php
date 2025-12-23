<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
    use HasFactory;

    // 💡 Tabel yang digunakan
    protected $table = 'topping';

    // 💡 Primary key custom
    protected $primaryKey = 'idTopping';

    // 💡 Field yang boleh diisi massal
    protected $fillable = [
        'namaTopping',
    ];

    // 💡 Relasi ke ItemPesanan
    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class, 'idTopping', 'idTopping');
    }
}
