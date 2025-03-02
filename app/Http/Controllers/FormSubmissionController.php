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
        if ($user->role === User::PARTNER_USER) {
            $query->where('user_id', $user->id);
        }

        // Obtener resultados
        $formSubmissions = $query->get();

        $role_admin = User::ADMIN_USER;

        return view('form_submissions.index', compact('formSubmissions', 'user', 'role_admin'));
    }

    public function show(FormSubmission $formSubmission)
    {

        $user = Auth::user();

        // Si el usuario NO es admin y el formulario no le pertenece, redirigir con error
        if ($user->role !== User::ADMIN_USER && $formSubmission->user_id !== $user->id) {
            return back()->with('error', 'No tienes permiso para ver el contenido.');
        }

        $data = json_decode($formSubmission->data, true); // Convierte JSON en array

        $responses = $formSubmission->formResponses;

        $role_admin = User::ADMIN_USER;

        return view('form_submissions.show', compact('formSubmission', 'user', 'data', 'role_admin', 'responses'));
    }
}
