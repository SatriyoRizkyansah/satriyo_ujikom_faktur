<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nama_produk',
        'price',
        'jenis',
        'stock',
    ];

    public function detailFaktur()
    {
        return $this->hasMany(DetailFaktur::class, 'id_produk', 'id_produk');
    }
}
