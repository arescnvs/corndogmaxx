<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    // Pastikan Laravel tahu tabelnya pakai bentuk singular
    protected $table = 'pelanggan';
    protected $primaryKey = 'idPelanggan';

    // Field yang bisa diisi lewat create() atau firstOrCreate()
    protected $fillable = [
        'namaPelanggan',
        'alamat',
        'noHP',
    ];
}
