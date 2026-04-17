<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Resources\ProdukResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Produk;
use App\Models\ProdukImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


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
        $data = $request->validated();

        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');
            $filename = time(). '-'. $file->getClientOriginalName();
            $destinationPath = storage_path('app/public/produk/'. $filename);
            $data['gambar'] = $destinationPath;

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());
            $image->scale(width: 800);
            $image->save($destinationPath);
            $data['gambar'] = 'produk/'. $filename;
        }

        $produk = Produk::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dibuat',
            'data' => new ProdukResource($produk)
        ], 201);
    }

    public function uploadImages(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $request->validate([
            'gambar' => 'required|array',
            'gambar.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $manager = new ImageManager(new Driver());        
        $images = [];

        foreach ($request->file('gambar') as $file) {
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $destinationPath = storage_path("app/public/produk/" . $filename);
            $image = $manager->read($file->getRealPath());

            if ($image->width() > 800) {
                $image->scale(width: 800);
            }

            $image->save($destinationPath, quality: 80);

            $path = "produk/" . $filename;

            // simpan ke tabel relasi
            $img = ProdukImage::create([
                'produk_id' => $produk->id,
                'path' => $path
            ]);
            $images[] = $img;
        }

        return response()->json([
            'success' => true,
            'message' => 'Multiple images berhasil diupload',
            'data' => $images
        ]);
    }

    public function updateImage(Request $request, $id) 
    {
        $produkImage = ProdukImage::findOrFail($id);

        $request->validate([
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($produkImage->path) {
            Storage::disk('public')->delete($produkImage->path);
        }
        $file = $request->file('gambar');
        $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
        $destinationPath = storage_path("app/public/produk/" . $filename);

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        if ($image->width() > 800) {
            $image->scale(width: 800);
        }

        $image->save($destinationPath, quality: 80);
        $path = "produk/" . $filename;

        // update path di database
        $produkImage->update([
            'path' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil diupdate',
            'data' => $produkImage
        ]);        
    }

    public function deleteImage($id)
    {
        $produkImage = ProdukImage::findOrFail($id);

        if ($produkImage->path) {
            Storage::disk('public')->delete($produkImage->path);
        }
        $produkImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus'
        ]);
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
        $data = $request->validated();

        if (empty($data)) {
            return response()->json([
                'message' => 'Tidak ada data yang diupdate'
            ]);
        }

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $file = $request->file('gambar');
            $filename = time(). '_'. $file->getClientOriginalName();
            $destinationPath = storage_path('app/public/produk'. $filename);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());

            if ($image->width() > 800) {
                $image->scale(width: 800);
            }

            $image->save($destinationPath, quality: 80);

            $data['gambar'] = 'produk/'. $filename;
        }

        $produk->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Diupdate',
            'data' => new ProdukResource($produk)
        ]);
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->gambar) {
                Storage::disk('publik')->delete($produk->gambar);
            }
        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'data berhasil dihapus'
        ]);
    }
}
