<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PJBLKelompokFormRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ganti dengan logika otorisasi yang sesuai jika diperlukan
    }

    public function rules()
    {
        return [
            // Validasi untuk nama kelompok
            'kelompok' => [
                'required',
                'string',
                'max:255',
                // Validasi untuk memastikan nama kelompok unik dalam tabel kelompok
                'unique:kelompoks,kelompok' // Pastikan nama kelompok belum ada di tabel 'kelompoks'
            ],
            // Validasi untuk anggota
            'anggotas' => 'required|array',
            'anggotas.*' => 'exists:users,id',
            // Validasi untuk ketua
            'ketua_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'kelompok.unique' => 'Nama kelompok ini sudah ada. Silakan pilih nama yang lain.',
        ];
    }
}