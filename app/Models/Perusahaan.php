<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $primaryKey = 'id_perusahaan';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'no_telp',
        'fax',
    ];

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'id_perusahaan', 'id_perusahaan');
    }
}
