<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
        'kategori',
        'expiredDate',
        'rating',
    ];

    public function image()
    {
        return $this->hasMany(ProdukImage::class);
    }
}
