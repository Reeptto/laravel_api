<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreProdukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post') && !$this->has('_method') ) {

        return [
            'kode_barang' => 'required|string|unique:produks,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|numeric|min:0',
            // 'gambar' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kategori' => 'required|string',
            'expiredDate' => 'nullable|date',
            'rating' => 'nullable|numeric|min:0|max:5',
        ];
    }

        return [
            'kode_barang' => [
                    'sometimes',
                    'string',
                    Rule::unique('produks', 'kode_barang')->ignore($this->route('produk'))
                ],
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|numeric|min:0',
            // 'gambar' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kategori' => 'required|string',
            'expiredDate' => 'nullable|date',
            'rating' => 'nullable|numeric|min:0|max:5',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
        response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
                ], 422
            )
        );
    }
}
