<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Override;

class ItemRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $token = $this->route('item');

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('items', 'code')->ignore(
                    $token ? rescue(function () use ($token) {
                        return Crypt::decrypt($token);
                    }, null, false) : null
                ),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:20'],
            'jumlah' => ['required', 'integer', 'min:0', 'max:1000000'],
        ];
    }

    public function itemData(): array
    {
        $data = $this->validated();

        return [
            'code' => $data['kode'],
            'product_name' => $data['nama'],
            'product_category' => $data['kategori'],
            'quantity' => $data['jumlah']
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode barang wajib diisi.',
            'kode.unique' => 'Kode barang sudah digunakan.',
            'kode.max' => 'Kode barang max.10 karakter.',
            'nama.required' => 'Nama barang wajib diisi.',
            'kategori.required' => 'Kategori barang wajib diisi.',
            'jumlah.required' => 'Jumlah stok harus diisi.',
            'jumlah.min' => 'Jumlah stok minimal 0.',
        ];
    }
}
