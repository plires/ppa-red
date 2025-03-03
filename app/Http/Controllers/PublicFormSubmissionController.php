<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormSubmission;

class PublicFormSubmissionController extends Controller
{
    public function show($token)
    {
        $formSubmission = FormSubmission::where('secure_token', $token)->firstOrFail();

        $data = json_decode($formSubmission->data, true); // Convierte JSON en array

        $responses = $formSubmission->formResponses;

        return view('public-forms.show', compact('formSubmission', 'data', 'responses'));
    }

    public function store(Request $request)
    {
        return 'llego';
    }
}
