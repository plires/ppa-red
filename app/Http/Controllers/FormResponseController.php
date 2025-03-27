<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FormResponse;
use App\Models\FormSubmission;
use App\Models\FormSubmissionStatus;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendFormResponseEmailToUser;
use App\Http\Requests\FormResponseRequest;
use App\Models\FormSubmissionNotification;

class FormResponseController extends Controller
{

    public function store(FormResponseRequest $request)
    {

        $formResponse = FormResponse::create($request->validated());
        $data = json_decode($formResponse->formSubmission->data, true); // Convierte JSON en array

        // Enviar el correo en segundo plano
        SendFormResponseEmailToUser::dispatch($formResponse, $data);

        // Actualizar el estado del FormSubmission
        $formSubmission = FormSubmission::findOrFail($request['form_submission_id']);
        $idStatus = FormSubmissionStatus::getIdByName(FormSubmissionStatus::STATUS_RESPONDIO_PARTNER);
        $formSubmission->form_submission_status_id = $idStatus;
        $formSubmission->save();

        return back()->with('success', 'El mensaje se envió correctamente.');
    }

    public function markAsReadAndRedirect(FormResponse $formResponse)
    {

        // Obtengo las respuestas asociadas de un formsubmission a partir de un FormResponse
        $responses = $formResponse->formSubmission->formResponses->where('is_system', 0);;

        // Marcar todas como leídas
        foreach ($responses as $response) {
            $response->markAsRead();
        }

        return redirect()->route('form_submissions.show', $formResponse->formSubmission->id);
    }

    public function markAsReadAllResponses(User $user)
    {

        dd($user);
        $unread_responses = FormSubmissionNotification::whereHas('formSubmission', function ($query) {
            // Filtra los form submissions del usuario autenticado
            $query->where('user_id', Auth::id());
        })
            ->where('is_read', 0) // Filtra las notificaciones no leídas
            ->get();

        // Marcar todas como leídas
        foreach ($unread_responses as $response) {
            $response->markAsRead();
        }

        return back()->with('success', 'Todas las notificaciones fueron marcadas como leídas.');
    }
}
