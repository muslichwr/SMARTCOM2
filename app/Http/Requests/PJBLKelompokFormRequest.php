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
            'kelompok' => 'required|string|max:255',
            'anggotas' => 'required|array',
            'anggotas.*' => 'exists:users,id',
            'ketua_id' => 'required|exists:users,id',
        ];
    }
}