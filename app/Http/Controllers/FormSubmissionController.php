<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSubmissionRequest;
use App\Jobs\SendPartnerReassignmentEmails;
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
        $query = FormSubmission::latest();

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

        $partners = [];
        if ($user->role === User::ADMIN_USER) {
            $partners = User::where('role', User::PARTNER_USER)
                ->orderBy('name')
                ->get(['id', 'name', 'email']);
        }

        return Inertia::render('FormSubmissions/Show', [
            'formSubmission' => $formSubmission->load(['status', 'locality.zone', 'locality.province', 'user']),
            'formData' => $data,
            'responses' => $responses->load('user'),
            'partners' => $partners,
        ]);
    }

    public function reassign(Request $request, FormSubmission $formSubmission)
    {
        $validated = $request->validate([
            'partner_id' => ['required', 'exists:users,id', function ($attr, $value, $fail) {
                if (User::find($value)?->role !== User::PARTNER_USER) {
                    $fail('El usuario seleccionado no es un partner.');
                }
            }],
        ]);

        if ((int) $formSubmission->user_id === (int) $validated['partner_id']) {
            return back()->with('error', 'El partner seleccionado ya está asignado a esta consulta.');
        }

        $outgoingPartner = $formSubmission->user;
        $incomingPartner = User::findOrFail($validated['partner_id']);
        $data = json_decode($formSubmission->data, true);

        $formSubmission->update(['user_id' => $incomingPartner->id]);

        SendPartnerReassignmentEmails::dispatch(
            $formSubmission->fresh(['status', 'locality', 'province', 'zone']),
            $outgoingPartner,
            $incomingPartner,
            $data,
        );

        return back()->with('success', "Consulta reasignada a {$incomingPartner->name} correctamente.");
    }

    public function update(FormSubmissionRequest $request, FormSubmission $formSubmission)
    {
        $formSubmission->update($request->validated());

        return redirect()->back()->with('success', 'El Formulario fue cerrado exitosamente.');
    }
}
