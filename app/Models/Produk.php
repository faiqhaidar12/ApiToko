<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'gambar'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
