<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZoneRequest extends FormRequest
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

        // Verificamos qué acción se está ejecutando
        if ($this->isMethod('delete')) {
            return []; // No aplicamos validaciones generales al eliminar
        }

        return [
            'name' => 'required|string|max:255',
            'province_id' => ['required', 'numeric', 'exists:provinces,id'],
        ];
    }

    public function withValidator($validator)
    {
        if ($this->isMethod('delete')) {
            $validator->after(function ($validator) {
                $zone = $this->route('zone'); // Obtiene la provincia de la ruta

                if ($zone->localities()->exists()) {
                    $validator->errors()->add('zone', 'No puedes eliminar esta zona porque tiene localidades asociadas.');
                }
            });
        }
    }
}
