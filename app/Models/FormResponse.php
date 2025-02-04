<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FormSubmission;
use App\Models\User;

class FormResponse extends Model
{
    use HasFactory;

    public function formSubmission()
    {
        return $this->belongsTo(FormSubmission::class);
    }
}
