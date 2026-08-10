<?php

namespace App\Http\Controllers;

use App\Models\FormSubmissionNotification;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormNotificationController extends Controller
{
    public function markAsReadAndRedirect($notificationId, $formSubmissionId)
    {
        $notification = FormSubmissionNotification::with('formSubmission')->findOrFail($notificationId);

        // Un partner sólo puede tocar las notificaciones de sus propias
        // consultas. Antes se buscaba por id sin comprobar propiedad, así que
        // cualquier partner podía marcar como leída una notificación ajena.
        // Se responde 404 y no 403 para no confirmarle a un tercero que la
        // notificación existe.
        $user = Auth::user();

        if (! $user->isAdmin() && $notification->formSubmission?->user_id !== $user->id) {
            throw new NotFoundHttpException;
        }

        $notification->markAsRead();

        return redirect()->route('form_submissions.show', $formSubmissionId);
    }

    public function markAsReadAllNotifications()
    {
        $unread_notifications = FormSubmissionNotification::whereHas('formSubmission', function ($query) {
            // Filtra los form submissions del usuario autenticado
            $query->where('user_id', Auth::id());
        })
            ->where('is_read', 0) // Filtra las notificaciones no leídas
            ->get();

        // Marcar todas como leídas
        foreach ($unread_notifications as $notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Todas las notificaciones fueron marcadas como leídas.');
    }
}
