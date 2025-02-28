<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

        $userId = $this->route('partner')?->id ?? null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'phone' => 'required|string|max:255',
            'change_password' => 'nullable|boolean',
            'password' => 'sometimes|required_if:change_password,1|string|min:8|confirmed',
            'password_confirmation' => 'sometimes|required_if:change_password,1|string|min:8',
        ];
    }

    public function withValidator($validator)
    {
        if ($this->isMethod('delete')) {
            $validator->after(function ($validator) {
                $user = $this->route('partner');

                if ($user->localities()->exists()) {
                    $validator->errors()->add('user', 'No puedes eliminar este partner porque tiene una/s localidad/es asignadas.');
                }
            });
        }
    }
}
