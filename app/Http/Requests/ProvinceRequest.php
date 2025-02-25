<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permitir que cualquiera use este request (se puede personalizar)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $provinceId = $this->route('province')?->id ?? null;

        return [
            'name' => 'required|string|max:255|unique:provinces,name,' . $provinceId,
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $province = $this->route('province'); // Obtiene la provincia de la ruta

            if ($province->localities()->exists() || $province->zones()->exists()) {
                $validator->errors()->add('province', 'No puedes eliminar esta provincia porque tiene zonas o localidades asociadas.');
            }
        });
    }
}
