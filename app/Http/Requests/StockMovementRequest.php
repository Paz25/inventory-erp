<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Override;

class StockMovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        $this->merge([
            'item_id' => Crypt::decrypt($this->item_id),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_id' => ['required', Rule::exists('items', 'id')->whereNull('deleted_at')],
            'tipe' => ['required', Rule::in(['initial', 'masuk', 'keluar'])],
            'jumlah' => ['required', 'integer', 'min:1', 'max:1000000'],
        ];
    }

    public function stockMovementData(): array
    {
        $data = $this->validated();

        return [
            'item_id' => $data['item_id'],
            'type' => $data['tipe'],
            'quantity' => $data['jumlah']
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'item_id.required' => 'Barang wajib dipilih.',
            'item_id.exists' => 'Barang tidak ditemukan.',
            'tipe.required' => 'Tipe stok wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ];
    }
}
