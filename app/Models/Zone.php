<?php

namespace App\Models;

use App\Models\Locality;
use App\Models\Province;
use App\Models\FormSubmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    /** @use HasFactory<\Database\Factories\ZoneFactory> */
    use HasFactory;

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function localities()
    {
        return $this->hasMany(Locality::class);
    }

    /**
     * Relación uno a muchos: Una zona puede tener varios envíos de formularios.
     */
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
