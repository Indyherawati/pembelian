<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $guarded = [];

    public static function getKodeFaktur()
    {
        $sql = "SELECT IFNULL(MAX(no_faktur), 'PB-0000000') as no_faktur FROM pembelian";
        $kodefaktur = DB::select($sql);
        $kd = $kodefaktur[0]->no_faktur;

        $noawal = substr($kd, -7);
        $noakhir = (int)$noawal + 1;
        return 'PB-' . str_pad($noakhir, 7, "0", STR_PAD_LEFT);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function pembelianBarangs()
    {
        return $this->hasMany(PembelianBarang::class, 'pembelian_id');
    }
}