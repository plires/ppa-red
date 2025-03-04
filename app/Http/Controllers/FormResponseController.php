<?php

namespace App\Http\Controllers;

use App\Models\FormResponse;
use App\Mail\FormResponseMailToUser;
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

        return back()->with('success', 'El mensaje se envió correctamente.');
    }
}
