<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FormSubmission extends Model
{
    /** @use HasFactory<\Database\Factories\FormSubmissionFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['user_id', 'province_id', 'zone_id', 'locality_id', 'data', 'form_submission_status_id', 'closure_reason'];

    protected $hidden = ['secure_token'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($formSubmission) {
            $formSubmission->secure_token = Str::random(32); // Token de 32 caracteres
        });
    }

    /**
     * Relación muchos a uno: Una Region pertenece a una provincia.
     */
    public function province()
    {
        return $this->belongsTo(Province::class)->withTrashed();
    }

    /**
     * Relación muchos a uno: Un envío de formulario tiene una zona.
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class)->withTrashed();
    }

    /**
     * Relación muchos a uno: Un envío de formulario tiene una localidad.
     */
    public function locality()
    {
        return $this->belongsTo(Locality::class)->withTrashed();
    }

    /**
     * Relación muchos a uno: Un envío pertenece a un usuario (o admin).
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Relación muchos a uno: Un envio pertenece a un socio.
     */
    public function partner()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function formResponses()
    {
        return $this->hasMany(FormResponse::class)->withTrashed();
    }

    /**
     * Relación muchos a uno: Un envío de formulario tiene un estado.
     */
    public function status()
    {
        return $this->belongsTo(FormSubmissionStatus::class, 'form_submission_status_id');
    }

    /**
     * Consultas que todavía esperan acción de alguien.
     *
     * Se pregunta por la ausencia de un estado cerrado y no por la presencia de
     * uno abierto: si aparece un estado nuevo, o si un envío quedara sin estado,
     * cae del lado de "abierta" y frena el borrado del partner en vez de dejarlo
     * pasar en silencio.
     */
    public function scopeOpen($query)
    {
        return $query->whereDoesntHave(
            'status',
            fn ($q) => $q->whereIn('name', FormSubmissionStatus::CLOSED_STATUSES)
        );
    }

    public function notifications()
    {
        return $this->hasMany(FormSubmissionNotification::class, 'form_submission_id');
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/y');
    }
}
