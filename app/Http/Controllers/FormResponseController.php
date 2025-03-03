<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FormResponse;
use Illuminate\Http\Request;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FormResponseRequest;

class FormResponseController extends Controller
{

    public function store(FormResponseRequest $request)
    {

        FormResponse::create($request->validated());

        return back()->with('success', 'El mensaje se envió correctamente.');
    }
}
