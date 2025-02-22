<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocalityRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'zone_id' => ['sometimes', 'numeric', 'exists:zones,id'],
            'province_id' => ['required', 'numeric', 'exists:provinces,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
        ];
    }
}
