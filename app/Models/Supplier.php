<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
    ];

    // relasi ke pembelian
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }
}