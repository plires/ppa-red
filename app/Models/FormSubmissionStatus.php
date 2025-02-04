<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FormSubmission;

class FormSubmissionStatus extends Model
{
    /** @use HasFactory<\Database\Factories\FormSubmissionStatusFactory> */
    use HasFactory;

    const STATUS_EN_CURSO = 'En Curso';
    const STATUS_DEMORADO = 'Demorado';
    const STATUS_COMPLETO = 'Completo';
    const STATUS_CERRADO = 'Cerrado';
    const STATUS_PENDIENTE = 'Pendiente';

    /**
     * Relación uno a muchos: Un estado puede tener muchos envíos de formularios.
     */
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
