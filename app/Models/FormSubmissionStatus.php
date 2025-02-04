<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FormSubmission;

class FormSubmissionStatus extends Model
{
    /** @use HasFactory<\Database\Factories\FormSubmissionStatusFactory> */
    use HasFactory;

    const STATUS_PENDIENTE_RTA_DE_PARTNER = 'Pendiente de Respuesta Del Partner';
    const STATUS_RESPONDIO_PARTNER = 'Respondido Por El Partner';
    const STATUS_DEMORADO_POR_PARTNER = 'Demorado - Sin Respuesta Del Partner (48h)';
    const STATUS_CERRADO_SIN_RTA_PARTNER = 'Cerrado - Sin Respuesta Del Partner';
    const STATUS_CERRADO_SIN_RTA_USUARIO = 'Cerrado - Sin Respuesta Del Usuario';
    const STATUS_CERRADO_POR_PARTNER = 'Cerrado Por El Partner';

    /**
     * Relación uno a muchos: Un estado puede tener muchos envíos de formularios.
     */
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
