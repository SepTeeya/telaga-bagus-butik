<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';

    protected $guarded = [];

    //Relasi balik ke Pelanggan dan User
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function penjahit()
    {
        return $this->belongsTo(User::class, 'id_penjahit', 'id_user');
    }

    // Relasi: Satu Pesanan memiliki Satu data Ukuran
    public function ukuran()
    {
        return $this->hasOne(Ukuran::class, 'id_pesanan', 'id_pesanan');
    }
}
