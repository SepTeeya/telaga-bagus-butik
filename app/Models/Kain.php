<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kain extends Model
{
    protected $table = 'kains';
    protected $primaryKey = 'id_kain';

    protected $guarded = [];

    // Accessor status ketersediaan stok
    public function getStatusStokAttribute()
    {
        if ($this->stok <= 0) {
            return 'habis';
        } elseif ($this->stok <= 5) {
            return 'menipis';
        }
        return 'tersedia';
    }
}

