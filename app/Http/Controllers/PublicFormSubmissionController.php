<?php

namespace App\Http\Controllers;

use App\Jobs\SendFormResponseEmailToPartner;
use App\Jobs\SendFormSubmissionConfirmationEmail;
use App\Jobs\SendFormSubmissionUnassignedEmailToAdmin;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use App\Models\Locality;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicFormSubmissionController extends Controller
{
    public function show($token)
    {
        $formSubmission = FormSubmission::where('secure_token', $token)
            ->with(['status', 'locality', 'zone', 'province', 'user', 'formResponses.user'])
            ->first();

        if (! $formSubmission) {
            return Inertia::render('PublicForms/NotFound');
        }

        $data = json_decode($formSubmission->data, true);

        return Inertia::render('PublicForms/Show', [
            'formSubmission' => $formSubmission,
            'formData' => $data,
            'isClosed' => $formSubmission->status->isClosed(),
            'closedByPartner' => $formSubmission->status->name === FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string|max:65535',
            'province_id' => 'required|exists:provinces,id',

            // La zona enviada tiene que ser exactamente la de la localidad. El
            // formulario de la landing encadena provincia → zona → localidad,
            // así que siempre coinciden; esta regla cubre el POST armado a mano.
            'zone_id' => ['nullable', 'exists:zones,id', function ($attribute, $value, $fail) use ($request) {
                $locality = Locality::find($request->input('locality_id'));

                if (! $locality) {
                    return;
                }

                if ((int) $locality->zone_id !== (int) $value) {
                    $fail('La zona seleccionada no corresponde a la localidad indicada.');
                }
            }],

            // Sin esta regla se podían crear consultas con provincia y localidad
            // de jerarquías distintas: los reportes quedaban inconsistentes y la
            // consulta caía en el partner equivocado.
            'locality_id' => ['required', 'exists:localities,id', function ($attribute, $value, $fail) use ($request) {
                $locality = Locality::find($value);

                if (! $locality) {
                    return;
                }

                if ((int) $locality->province_id !== (int) $request->input('province_id')) {
                    $fail('La localidad seleccionada no pertenece a la provincia indicada.');
                }
            }],
        ]);

        $locality = Locality::findOrFail($validated['locality_id']);
        $statusId = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER);

        $formSubmission = FormSubmission::create([
            'user_id' => $locality->user_id,
            'province_id' => $validated['province_id'],
            'zone_id' => $validated['zone_id'] ?? null,
            'locality_id' => $validated['locality_id'],
            'data' => json_encode([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'message' => $validated['message'],
            ]),
            'form_submission_status_id' => $statusId,
        ]);

        // Crear el primer mensaje de la conversación con el mensaje del solicitante
        $formResponse = FormResponse::create([
            'form_submission_id' => $formSubmission->id,
            'user_id' => null,
            'message' => $validated['message'],
            'is_system' => false,
        ]);

        // Notificar al partner asignado, o al administrador si la localidad no tiene partner
        $partner = $formSubmission->user;
        if ($partner && $partner->email) {
            SendFormResponseEmailToPartner::dispatch($formResponse, $formSubmission, $validated);
        } else {
            SendFormSubmissionUnassignedEmailToAdmin::dispatch($formSubmission, $validated);
        }

        // Confirmar recepción al usuario que realizó la consulta
        SendFormSubmissionConfirmationEmail::dispatch($formSubmission, $validated);

        return redirect()->route('public.form_submission.show', $formSubmission->secure_token)
            ->with('success', 'Tu consulta fue enviada correctamente. Te responderemos a la brevedad.');
    }
}
