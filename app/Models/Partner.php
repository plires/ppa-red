<?php

namespace App\Models;

use App\Models\Locality;
use App\Models\FormSubmission;

class Partner extends User
{

    protected $table = 'users'; // Forzar que use la tabla "users"

    public function localities()
    {
        return $this->hasMany(Locality::class, 'user_id'); // Especificar la clave foránea correcta
    }

    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class, 'user_id'); // Especificar la clave foránea correcta
    }
}
