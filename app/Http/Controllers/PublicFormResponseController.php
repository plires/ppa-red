<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicFormResponseRequest;
use App\Jobs\SendFormResponseEmailToPartner;
use App\Jobs\SendFormResponseUnassignedEmailToAdmin;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;

class PublicFormResponseController extends Controller
{
    public function store(PublicFormResponseRequest $request)
    {

        $formResponse = FormResponse::create($request->validated());

        $formSubmission = $formResponse->formSubmission;
        $data = json_decode($formResponse->formSubmission->data, true); // Convierte JSON en array

        // Notificar al partner asignado, o al administrador si la localidad no tiene partner
        if ($formSubmission->user && $formSubmission->user->email) {
            SendFormResponseEmailToPartner::dispatch($formResponse, $formSubmission, $data);
        } else {
            SendFormResponseUnassignedEmailToAdmin::dispatch($formResponse, $formSubmission, $data);
        }

        // Actualizar el estado del FormSubmission
        $formSubmission = FormSubmission::findOrFail($request['form_submission_id']);
        $idStatus = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);
        $formSubmission->form_submission_status_id = $idStatus;
        $formSubmission->save();

        return back()->with('success', 'El mensaje se envió correctamente. En breve será respondido.');
    }
}
