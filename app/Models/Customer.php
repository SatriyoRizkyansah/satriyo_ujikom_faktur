<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'id_customer';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nama_customer',
        'perusahaan_cust',
        'alamat',
    ];

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'id_customer', 'id_customer');
    }
}
