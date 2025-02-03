<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;

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

        $role_admin = User::ADMIN_USER;

        return view('form_submissions.index', compact('formSubmissions', 'user', 'role_admin'));
    }

    public function show(FormSubmission $formSubmission)
    {
        dd($formSubmission);
    }
}
