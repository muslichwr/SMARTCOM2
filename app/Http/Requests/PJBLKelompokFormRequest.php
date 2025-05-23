<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PJBLKelompokFormRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ganti dengan logika otorisasi yang sesuai jika diperlukan
    }

    public function rules()
    {
        // Mendapatkan kelompok ID dari route parameter jika metode adalah PUT/PATCH (update)
        $kelompokId = $this->route('slug') ? 
            \App\Models\Kelompok::where('slug', $this->route('slug'))->value('id') : null;

        $rules = [
            // Validasi untuk nama kelompok - menggunakan Rule::unique untuk ignore ID saat update
            'kelompok' => [
                'required',
                'string',
                'max:255',
                // Abaikan ID kelompok saat ini saat melakukan validasi unique
                Rule::unique('kelompoks')->ignore($kelompokId)
            ],
        ];

        // Validasi berbeda untuk create dan update
        if ($this->isMethod('post')) { // Create
            $rules['anggotas'] = 'required|array';
            $rules['anggotas.*'] = 'exists:users,id';
            $rules['ketua_id'] = 'required|exists:users,id';
        } else { // Update
            $rules['anggotas'] = 'sometimes|array';
            $rules['anggotas.*'] = 'exists:users,id';
            $rules['ketua_id'] = 'sometimes|exists:users,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'kelompok.unique' => 'Nama kelompok ini sudah ada. Silakan pilih nama yang lain.',
            'anggotas.required' => 'Pilih minimal satu anggota kelompok.',
            'ketua_id.required' => 'Pilih ketua kelompok.',
        ];
    }
}