<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSubmissionRequest;
use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

        return Inertia::render('FormSubmissions/Index', [
            'formSubmissions' => $formSubmissions->load(['status', 'locality', 'user']),
        ]);
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

        $unread_responses = $responses
            ->filter(fn ($response) => $response->is_read == 0 && $response->is_system == 0);

        $unread_responses->each(function ($response) {
            $response->is_read = 1;
            $response->save();
        });

        return Inertia::render('FormSubmissions/Show', [
            'formSubmission' => $formSubmission->load(['status', 'locality.zone', 'locality.province', 'user']),
            'formData' => $data,
            'responses' => $responses->load('user'),
        ]);
    }

    public function update(FormSubmissionRequest $request, FormSubmission $formSubmission)
    {
        $formSubmission->update($request->validated());

        return redirect()->back()->with('success', 'El Formulario fue cerrado exitosamente.');
    }
}
