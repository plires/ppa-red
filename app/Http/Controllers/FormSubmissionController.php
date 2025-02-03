<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Auth;

class FormSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = FormSubmission::query();

        // Si el usuario es 'partner', solo puede ver sus propios formularios
        if ($user->role === 'partner') {
            $query->where('user_id', $user->id);
        }

        // Obtener resultados
        $formSubmissions = $query->get();

        return view('form_submissions.index', compact('formSubmissions'));
    }
}
