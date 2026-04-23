<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Models\Pembelian;
use App\Models\Barang;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\DB;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pembelian';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Informasi Pembelian')
                        ->schema([
                            TextInput::make('no_faktur')
                                ->default(fn () => Pembelian::getKodeFaktur())
                                ->label('Nomor Faktur')
                                ->readonly()
                                ->required(),
                            DateTimePicker::make('tgl')->label('Tanggal Transaksi')->default(now())->required(),
                            Select::make('supplier_id')
                                ->label('Supplier')
                                ->options(Supplier::pluck('nama', 'id'))
                                ->searchable()
                                ->required(),
                        ])->columns(3),

                    Wizard\Step::make('Pilih Barang')
                        ->schema([
                            Repeater::make('pembelianBarangs')
                                ->relationship()
                                ->schema([
                                    Select::make('barang_id')
                                        ->label('Barang')
                                        ->options(Barang::pluck('nama_barang', 'id'))
                                        ->reactive()
                                        ->searchable()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $barang = Barang::find($state);
                                            $set('harga', $barang ? $barang->harga_beli : 0);
                                        })
                                        ->required(),
                                    TextInput::make('harga')
                                        ->label('Harga Beli Satuan')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp'),
                                    TextInput::make('jml')
                                        ->label('Jumlah Qty')
                                        ->numeric()
                                        ->default(1)
                                        ->required(),
                                ])->columns(3),
                        ]),

                    Wizard\Step::make('Konfirmasi & Proses')
                        ->schema([
                            Placeholder::make('info')
                                ->label('Penting!')
                                ->content('Klik tombol di bawah untuk finalisasi data.'),
                            
                            Forms\Components\Actions::make([
                                Forms\Components\Actions\Action::make('Proses Sekarang')
                                    ->action(function ($get, $record) {
                                        if (!$record) return;

                                        foreach ($get('pembelianBarangs') as $item) {
                                            $barang = Barang::find($item['barang_id']);
                                            if ($barang) {
                                                $barang->increment('stok', $item['jml']);
                                            }
                                        }

                                        $total = collect($get('pembelianBarangs'))
                                            ->sum(fn ($i) => ($i['harga'] ?? 0) * ($i['jml'] ?? 0));
                                        
                                        $record->update(['total' => $total]);
                                    })
                                    ->color('success')
                                    ->icon('heroicon-m-check-circle'),
                            ])
                        ]),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_faktur')->label('No Faktur')->searchable(),
                Tables\Columns\TextColumn::make('supplier.nama')->label('Supplier'),
                Tables\Columns\TextColumn::make('tgl')->label('Tanggal')->dateTime(),
                Tables\Columns\TextColumn::make('total')->label('Total')->money('IDR'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}