<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
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
        return [
            'npwp' => 'required|string|max:20',
            'nama_lengkap' => 'required|string|max:150',
            'no_ktp' => 'required|string|max:20',
            'alamat_ktp' => 'required|string',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'email' => 'required|email|max:100',
            'no_hp' => 'required|string|max:20',
            'no_telp_perusahaan' => 'required|string|max:20',
            'jenis_npwp' => 'required|in:Orang Pribadi,Badan,BUT',
            'kependudukan' => 'required|in:Dalam Negeri,Luar Negeri',
        ];
    }
}
