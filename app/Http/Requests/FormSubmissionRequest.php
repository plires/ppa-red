<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class FormSubmissionRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    // Agregar form_submission_status_id (estado "Cerrado Por El Partner")
    $this->merge([
      'form_submission_status_id' => FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER)
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
      'closure_reason' => 'required|string|min:10|max:65535', // min:10, max:65535 es el límite de un campo TEXT en MySQL
      'form_submission_id' => 'required|exists:form_submissions,id',
      'user_id' => 'required|exists:users,id',
      'form_submission_status_id' => 'required|exists:form_submission_statuses,id',
    ];
  }

  public function messages()
  {
    return [
      'closure_reason.required' => 'El mensaje es obligatorio.',
      'closure_reason.string' => 'El mensaje debe ser un texto.',
      'closure_reason.max' => 'El mensaje no puede superar los 65535 caracteres.',
      'closure_reason.min' => 'El mensaje debe tener al menos 10 caracteres.',
      'form_submission_id.required' => 'Falta el formulario asociado.',
      'form_submission_id.exists' => 'El formulario seleccionado no es válido.',
      'user_id.required' => 'Falta el usuario asociado.',
      'user_id.exists' => 'El usuario asociado no es válido.',
      'form_submission_status_id.required' => 'Debe proporcionarse un estado para actualizar el formulario.',
      'form_submission_status_id.exists' => 'El estado para actualizar este formulario no existe.',
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
