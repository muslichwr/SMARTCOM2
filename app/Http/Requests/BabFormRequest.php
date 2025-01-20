<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BabFormRequest extends FormRequest
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
            'materi_id' => 'required|integer|exists:materis,id',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'file' => 'nullable|mimes:pdf,mp4,avi,mov|max:10240',
            'video_url' => 'nullable|url',
        ];
    }
}
