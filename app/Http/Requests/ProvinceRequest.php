<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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

        // Verificamos qué acción se está ejecutando
        if ($this->isMethod('delete')) {
            return []; // No aplicamos validaciones generales al eliminar
        }

        $provinceId = $this->route('province')?->id ?? null;

        return [
            'name' => 'required|string|max:255|unique:provinces,name,'.$provinceId,
        ];
    }

    public function withValidator($validator)
    {
        if ($this->isMethod('delete')) {
            $validator->after(function ($validator) {
                $province = $this->route('province');

                if ($province->localities()->exists() || $province->zones()->exists()) {
                    $validator->errors()->add('province', 'No puedes eliminar esta provincia porque tiene zonas o localidades asociadas.');
                }
            });
        }
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->isMethod('delete')) {
            throw new HttpResponseException(
                redirect()->back()->with('error', $validator->errors()->first())
            );
        }

        parent::failedValidation($validator);
    }
}
