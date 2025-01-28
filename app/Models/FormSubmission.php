<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Province;
use App\Models\FormSubmissionStatus;

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
     * Relación muchos a uno: Un envío de formulario tiene un estado.
     */
    public function status()
    {
        return $this->belongsTo(FormSubmissionStatus::class, 'form_submission_status_id');
    }
}
