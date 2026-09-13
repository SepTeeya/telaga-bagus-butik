<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukuran extends Model
{
    use HasFactory;

    protected $table = 'ukurans';
    // Mengizinkan semua kolom diisi secara otomatis

    protected $primaryKey = 'id_ukuran';
    protected $guarded = [];

    // Relasi balik ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
