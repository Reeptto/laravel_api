<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode_barang' => $this->kode_barang,
            'nama_barang' => $this->nama_barang,
            'harga' =>  $this->harga,
            'quantity' => $this->stok,
            'deskripsi' => $this->deskripsi,
            'gambar' => $this->gambar ? asset('storage/'. $this->gambar) : null,
            'kategori' => $this->kategori,
            'expiredDate' => $this->expiredDate,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
