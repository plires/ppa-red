<?php

namespace App\Http\Controllers;

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
            ->firstOrFail();

        $data = json_decode($formSubmission->data, true);

        $closedStatuses = [
            FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER,
            FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER,
            FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO,
        ];

        return Inertia::render('PublicForms/Show', [
            'formSubmission' => $formSubmission,
            'formData' => $data,
            'isClosed' => in_array($formSubmission->status->name, $closedStatuses),
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
            'zone_id' => 'nullable|exists:zones,id',
            'locality_id' => 'required|exists:localities,id',
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

        return redirect()->route('public.form_submission.show', $formSubmission->secure_token)
            ->with('success', 'Tu consulta fue enviada correctamente. Te responderemos a la brevedad.');
    }
}
