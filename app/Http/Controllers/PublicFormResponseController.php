<?php

namespace App\Http\Controllers;

use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Jobs\SendFormResponseEmailToPartner;
use App\Http\Requests\PublicFormResponseRequest;

class PublicFormResponseController extends Controller
{
    public function store(PublicFormResponseRequest $request)
    {

        $formResponse = FormResponse::create($request->validated());

        $formSubmission = $formResponse->formSubmission;

        // Enviar el correo en segundo plano
        SendFormResponseEmailToPartner::dispatch($formResponse, $formSubmission);

        // Actualizar el estado del FormSubmission
        $formSubmission = FormSubmission::findOrFail($request['form_submission_id']);
        $idStatus = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);
        $formSubmission->form_submission_status_id = $idStatus;
        $formSubmission->save();

        return back()->with('success', 'El mensaje se envió correctamente. En breve será respondido.');
    }
}
