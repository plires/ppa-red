<?php

namespace App\Models;

use App\Models\User;
use App\Models\FormSubmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormResponse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['message', 'form_submission_id', 'user_id', 'is_system'];

    public function formSubmission()
    {
        return $this->belongsTo(FormSubmission::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
}
