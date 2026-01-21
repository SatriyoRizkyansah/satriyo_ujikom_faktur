<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailFaktur extends Model
{
    use HasFactory;

    protected $table = 'detail_faktur';
    protected $primaryKey = null;
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_produk',
        'no_faktur',
        'qty',
        'price',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
    ];

    public function faktur()
    {
        return $this->belongsTo(Faktur::class, 'no_faktur', 'no_faktur');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
