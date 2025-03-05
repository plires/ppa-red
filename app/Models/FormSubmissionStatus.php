<?php

namespace App\Models;

use App\Models\FormSubmission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSubmissionStatus extends Model
{
    /** @use HasFactory<\Database\Factories\FormSubmissionStatusFactory> */
    use HasFactory;

    const STATUS_PENDIENTE_RTA_DE_PARTNER = 'Pendiente de Respuesta Del Partner';
    const STATUS_RESPONDIO_PARTNER = 'Respondido Por El Partner';
    const STATUS_DEMORADO_POR_PARTNER = 'Demorado - Sin Respuesta Del Partner (48h)';
    const STATUS_CERRADO_SIN_RTA_PARTNER = 'Cerrado - Sin Respuesta Del Partner';
    const STATUS_CERRADO_SIN_RTA_USUARIO = 'Cerrado - Sin Respuesta Del Usuario';
    const STATUS_CERRADO_SIN_MAS_ACTIVIDAD = 'Cerrado - Sin Más Actividad';
    const STATUS_CERRADO_POR_EL_PARTNER = 'Cerrado Por El Partner';

    /**
     * Relación uno a muchos: Un estado puede tener muchos envíos de formularios.
     */
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public static function getIdByName($name)
    {
        return Cache::rememberForever("form_submission_status_id_{$name}", function () use ($name) {
            return self::where('status', $name)->value('id');
        });
    }
}
