<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Resources\ProdukResource;
use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        // Search
        if ($request->has('search')){
            $search = $request->search;

            $query->where('nama_barang', 'like', "%{$search}%")
                ->orWhere('kode_barang', 'like', "%{$search}%");
        }

        // Filter Kategori
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Sorting Harga
        if ($request->has('sort')) {
            $sort = $request->sort;

            if ($sort == 'harga_asc') {
                $query->orderBy('harga', 'asc');
            }

            if ($sort == 'harga_desc') {
                $query->orderBy('harga', 'desc');
            }
        }else {
            $query->latest();
        }
        
        $produk = $query->paginate(10);
        
        return response()->json([
            'success' => true,
            'message' => 'List Produk',
            'data' => ProdukResource::collection($produk),
            'pagination' => [
                'current_page' => $produk->currentPage(),
                'last_page' => $produk->lastPage(),
                'per_page' => $produk->perPage(),
                'total' => $produk->total(),

                'first_page_url' => $produk->url(1),
                'last_page_url' => $produk->url($produk->lastPage()),
                'nex_page_url' => $produk->nextPageUrl(),
                'prev_page_url' => $produk->previousPageUrl(),
                'from' => $produk->firstItem(),
                'to' => $produk->lastItem(),
            ]
        ]);
    }
    
    public function store(StoreProdukRequest $request)
    {
        $produk = Produk::create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dibuat',
            'data' => new ProdukResource($produk)
        ], 201);
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => new ProdukResource($produk)
        ]);

    }

    public function update(StoreProdukRequest $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Diupdate',
            'data' => new ProdukResource($produk)
        ]);
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'data berhasil dihapus'
        ]);
    }
}
