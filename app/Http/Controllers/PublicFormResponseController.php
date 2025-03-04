<?php

namespace App\Http\Controllers;

use App\Models\FormResponse;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormResponseMailToPartner;
use App\Http\Requests\PublicFormResponseRequest;

class PublicFormResponseController extends Controller
{
    public function store(PublicFormResponseRequest $request)
    {

        $formResponse = FormResponse::create($request->validated());

        $formSubmission = $formResponse->formSubmission;

        // Enviar el email
        Mail::to($formResponse->user->email)
            ->send(new FormResponseMailToPartner($formResponse, $formSubmission));

        // Actualizar el estado del FormSubmission
        $formSubmission = FormSubmission::findOrFail($request['form_submission_id']);
        $formSubmission->form_submission_status_id = 1;
        $formSubmission->save();

        return back()->with('success', 'El mensaje se envió correctamente. En breve será respondido.');
    }
}
