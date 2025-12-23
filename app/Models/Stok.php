<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';
    protected $primaryKey = 'idStok';
    public $timestamps = true;

    protected $fillable = [
        'idMenu',
        'idKasir',
        'jumlahStok',
    ];

    // Relasi ke Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'idMenu', 'idMenu');
    }

    // Relasi ke Kasir (user)
    public function kasir()
    {
        return $this->belongsTo(User::class, 'idKasir', 'idKasir');
    }
}
