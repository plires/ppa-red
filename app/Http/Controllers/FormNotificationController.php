<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Auth;
use App\Models\FormSubmissionNotification;
use Illuminate\Notifications\DatabaseNotification;


class FormNotificationController extends Controller
{
    public function markAsReadAndRedirect($notificationId, $formSubmissionId)
    {
        // Buscar la notificación del usuario autenticado
        $notification = FormSubmissionNotification::findOrFail($notificationId);

        if ($notification) {
            $notification->markAsRead(); // Marcar la notificación como leída
        }

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
