<?php

namespace App\Models;

use Database\Factories\FormSubmissionStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FormSubmissionStatus extends Model
{
    /** @use HasFactory<FormSubmissionStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    const STATUS_PENDIENTE_RTA_DE_PARTNER = 'Pendiente de Respuesta Del Partner';

    const STATUS_RESPONDIO_PARTNER = 'Respondido Por El Partner';

    const STATUS_DEMORADO_POR_PARTNER = 'Demorado - Sin Respuesta Del Partner (48h)';

    const STATUS_CERRADO_SIN_RTA_PARTNER = 'Cerrado - Sin Respuesta Del Partner';

    const STATUS_CERRADO_SIN_RTA_USUARIO = 'Cerrado - Sin Respuesta Del Usuario';

    const STATUS_CERRADO_POR_EL_PARTNER = 'Cerrado Por El Partner';

    /**
     * Estados terminales: la consulta ya no espera acción de nadie.
     *
     * La distinción importa fuera de lo cosmético. En una consulta cerrada el
     * partner es un hecho histórico —quién la atendió— y se conserva aunque se
     * lo elimine. En una abierta es una asignación operativa: quién tiene que
     * responder. Por eso no se puede eliminar un partner con consultas
     * abiertas, pero sí con todas cerradas.
     */
    const CLOSED_STATUSES = [
        self::STATUS_CERRADO_SIN_RTA_PARTNER,
        self::STATUS_CERRADO_SIN_RTA_USUARIO,
        self::STATUS_CERRADO_POR_EL_PARTNER,
    ];

    public function isClosed(): bool
    {
        return in_array($this->name, self::CLOSED_STATUSES, true);
    }

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
            return self::where('name', $name)->value('id');
        });
    }
}
