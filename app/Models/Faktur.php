<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
    use HasFactory;

    protected $table = 'faktur';
    protected $primaryKey = 'no_faktur';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'tgl_faktur',
        'due_date',
        'metode_bayar',
        'ppn',
        'dp',
        'grand_total',
        'user',
        'id_customer',
        'id_perusahaan',
    ];

    protected $casts = [
        'tgl_faktur' => 'date',
        'due_date' => 'date',
        'ppn' => 'decimal:2',
        'dp' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function detailFaktur()
    {
        return $this->hasMany(DetailFaktur::class, 'no_faktur', 'no_faktur');
    }
}
