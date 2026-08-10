<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        // Verificamos qué acción se está ejecutando
        if ($this->isMethod('delete')) {
            return []; // No aplicamos validaciones generales al eliminar
        }

        // zones.name tiene índice único en base. Sin esta regla el nombre
        // repetido llegaba al INSERT y reventaba con QueryException (500) en
        // vez de devolver un error de formulario.
        // No se excluyen las zonas borradas: el índice único de la base tampoco
        // las excluye, así que un nombre de zona en la papelera sigue ocupado.
        $zoneId = $this->route('zone')?->id ?? null;

        return [
            'name' => 'required|string|max:255|unique:zones,name,'.$zoneId,
            'province_id' => ['required', 'numeric', 'exists:provinces,id'],
        ];
    }

    public function withValidator($validator)
    {
        if ($this->isMethod('delete')) {
            $validator->after(function ($validator) {
                $zone = $this->route('zone');

                if ($zone->localities()->exists()) {
                    $validator->errors()->add('zone', 'No puedes eliminar esta zona porque tiene localidades asociadas.');
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
