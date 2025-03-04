<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class FormResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Agregar is_system ya que en este FormResponseRequest, siempre sera 1 (el mensaje lo envia el partner )
        $this->merge([
            'is_system' => 1,
        ]);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'message' => 'required|string|max:65535', // 65535 es el límite de un campo TEXT en MySQL
            'form_submission_id' => 'required|exists:form_submissions,id',
            'user_id' => 'required|exists:users,id',
            'is_system' => 'required|boolean', // Asegura que siempre tenga un valor y sea 0 o 1
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'El mensaje es obligatorio.',
            'message.string' => 'El mensaje debe ser un texto.',
            'message.max' => 'El mensaje no puede superar los 65535 caracteres.',
            'form_submission_id.required' => 'Falta el formulario asociado.',
            'form_submission_id.exists' => 'El formulario seleccionado no es válido.',
            'user_id.required' => 'Falta el usuario asociado.',
            'user_id.exists' => 'El usuario asociado no es válido.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            $formSubmission = FormSubmission::find($this->form_submission_id);

            // Si el formSubmission no existe (por algún error en las reglas de validación)
            if (!$formSubmission) {
                return;
            }

            // Si el usuario es "partner", verificar que el FormSubmission le pertenezca
            if ($user->role === User::PARTNER_USER && (int)$formSubmission->user_id !== (int)$user->id) {
                $validator->errors()->add('form_submission_id', 'No tienes permiso para agregar una respuesta a este formulario.');
            }

            // Verificar que el user_id (lo que viene del front) sea el mismo del user_id asignado en el formSubmission
            if ((int)$formSubmission->user_id !== (int)$this->user_id) {
                $validator->errors()->add('user_id', 'No tienes permiso para agregar una respuesta a este formulario.');
            }
        });
    }
}
