<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'nama',
        'alamat',
        'phone',
        'qty',
        'total_harga',
        'status',
        'produk_id'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
