<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Barang;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    protected function afterCreate(): void
    {
        $pembelian = $this->record;
        $total = 0;

        if ($pembelian->pembelianBarangs) {
            foreach ($pembelian->pembelianBarangs as $item) {
                $total += ($item->jml * $item->harga);

                $barang = Barang::find($item->barang_id);
                if ($barang) {
                    $barang->increment('stok', $item->jml);
                }
            }
        }

        $pembelian->update(['total' => $total]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}