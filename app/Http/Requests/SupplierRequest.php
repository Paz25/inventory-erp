<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class SupplierRequest extends FormRequest
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
        return [
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'alamat_perusahaan' => ['required', 'string', 'max:255'],
            'nama_pic' => ['required', 'string', 'max:255'],
            'no_telp_pic' => ['required', 'string', 'max:13'],
            'email_pic' => ['required', 'string', 'max:50']
        ];
    }

    public function supplierData(): array
    {
        $data = $this->validated();

        return [
            'supplier_name' => $data['nama_perusahaan'],
            'supplier_address' => $data['alamat_perusahaan'],
            'pic_name' => $data['nama_pic'],
            'pic_contact_number' => $data['no_telp_pic'],
            'pic_contact_email' => $data['email_pic']
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'nama_perusahaan.max' => 'Nama perusahaan max.255 karakter',
            'alamat_perusahaan.required' => 'Alamat perusahaan wajib diisi.',
            'alamat_perusahaan.max' => 'Alamat perusahaan max.255 karakter',
            'nama_pic.required' => 'Nama PIC wajib diisi.',
            'nama_pic.max' => 'Nama PIC max.255 karakter',
            'no_telp_pic.required' => 'No. telp PIC wajib diisi.',
            'no_telp_pic.max' => 'No. telp PIC max.13 karakter',
            'email_pic.required' => 'Email telp PIC wajib diisi.',
            'email_pic.email' => 'Format email PIC tidak valid.',
            'email_pic.max' => 'Email telp PIC max.50 karakter',
        ];
    }
}
