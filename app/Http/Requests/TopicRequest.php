<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TopicRequest extends FormRequest
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
        $topicId = $this->topic ? $this->topic->id : null;
    
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:topics,id',
            'is_active' => 'required|boolean'
        ];
    }
}
