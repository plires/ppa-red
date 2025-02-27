<?php

namespace App\Models;

use App\Models\User;
use App\Models\FormSubmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormResponse extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function formSubmission()
    {
        return $this->belongsTo(FormSubmission::class);
    }
}
