<?php

namespace App\Http\Controllers;

use App\Models\FormResponse;
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

        return back()->with('success', 'El mensaje se envió correctamente. En breve será respondido.');
    }
}
