<?php

namespace App\Http\Controllers;

use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Jobs\SendFormResponseEmailToUser;
use App\Http\Requests\FormResponseRequest;

class FormResponseController extends Controller
{

    public function store(FormResponseRequest $request)
    {

        $formResponse = FormResponse::create($request->validated());
        $data = json_decode($formResponse->formSubmission->data, true); // Convierte JSON en array

        // Enviar el correo en segundo plano
        SendFormResponseEmailToUser::dispatch($formResponse, $data);

        // Actualizar el estado del FormSubmission
        $formSubmission = FormSubmission::findOrFail($request['form_submission_id']);
        $idStatus = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER);
        $formSubmission->form_submission_status_id = $idStatus;
        $formSubmission->save();

        return back()->with('success', 'El mensaje se envió correctamente.');
    }
}
