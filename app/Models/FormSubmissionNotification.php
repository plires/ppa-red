<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmissionNotification extends Model
{
    protected $fillable = [
        'form_submission_id',
        'previous_status_id',
        'new_status_id',
        'closure_reason',
        'notification_details',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function formSubmission(): BelongsTo
    {
        return $this->belongsTo(FormSubmission::class);
    }

    public function previousStatus(): BelongsTo
    {
        return $this->belongsTo(FormSubmissionStatus::class, 'previous_status_id');
    }

    public function newStatus(): BelongsTo
    {
        return $this->belongsTo(FormSubmissionStatus::class, 'new_status_id');
    }

    // Método para marcar la notificación como leída
    public function markAsRead(): void
    {
        $this->is_read = true;
        $this->read_at = now();
        $this->save();
    }

    // Método para marcar la notificación como no leída
    public function markAsUnread(): void
    {
        $this->is_read = false;
        $this->read_at = null;
        $this->save();
    }

    // Scopes para filtrar por estado de lectura
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
