<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Zone;
use App\Models\Locality;
use App\Models\Province;
use App\Models\FormResponse;
use App\Models\FormSubmissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSubmission extends Model
{
    /** @use HasFactory<\Database\Factories\FormSubmissionFactory> */
    use HasFactory;
    use SoftDeletes;

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
        return $this->belongsTo(Partner::class, 'user_id')->withTrashed(); // Especificamos la clave foránea correcta
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

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/y');
    }
}
