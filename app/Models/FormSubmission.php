<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Province;
use App\Models\Zone;
use App\Models\Locality;
use App\Models\FormSubmissionStatus;
use Carbon\Carbon;

class FormSubmission extends Model
{
    /** @use HasFactory<\Database\Factories\FormSubmissionFactory> */
    use HasFactory;

    /**
     * Relación muchos a uno: Una Region pertenece a una provincia.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Relación muchos a uno: Un envío de formulario tiene una zona.
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Relación muchos a uno: Un envío de formulario tiene una localidad.
     */
    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    /**
     * Relación muchos a uno: Un envío pertenece a un usuario (o admin).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación muchos a uno: Un envio pertenece a un socio.
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'user_id'); // Especificamos la clave foránea correcta
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
