<?php

namespace App\Http\Controllers;

use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Mail\FormResponseMailToUser;
use App\Models\FormSubmissionStatus;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\FormResponseRequest;

class FormResponseController extends Controller
{

    public function store(FormResponseRequest $request)
    {

        $formResponse = FormResponse::create($request->validated());
        $data = json_decode($formResponse->formSubmission->data, true); // Convierte JSON en array

        // Enviar el email
        Mail::to($data['email'])
            ->send(new FormResponseMailToUser($formResponse, $data));

        // Actualizar el estado del FormSubmission
        $formSubmission = FormSubmission::findOrFail($request['form_submission_id']);
        $idStatus = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER);
        $formSubmission->form_submission_status_id = $idStatus;
        $formSubmission->save();

        return back()->with('success', 'El mensaje se envió correctamente.');
    }
}
