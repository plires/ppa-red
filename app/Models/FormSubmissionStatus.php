<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FormSubmission;

class FormSubmissionStatus extends Model
{
    /** @use HasFactory<\Database\Factories\FormSubmissionStatusFactory> */
    use HasFactory;

    /**
     * Relación uno a muchos: Un estado puede tener muchos envíos de formularios.
     */
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
