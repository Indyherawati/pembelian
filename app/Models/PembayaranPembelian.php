<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPembelian extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_pembelian';

    protected $fillable = ['pembelian_id', 'total_bayar', 'tgl_bayar'];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }
}

