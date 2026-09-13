<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected $primaryKey = 'id_user'; // Menyesuaikan nama primary key
    protected $fillable = [
        'nama_user',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class,'id_user','id_user');
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner' || $this->username === 'owner';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    public function isPenjahit(): bool
    {
        return $this->role === 'penjahit';
    }
}
