<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Partner;
use App\Models\Zone;
use App\Models\Province;

class Locality extends Model
{
    /** @use HasFactory<\Database\Factories\LocalityFactory> */
    use HasFactory;

    /**
     * Relación muchos a uno: Una localidad pertenece a un socio.
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'user_id'); // Especificamos la clave foránea correcta
    }

    /**
     * Relación muchos a uno: Una localidad pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class); // Especificamos la clave foránea correcta
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Relación uno a muchos: Una localidad tiene muchos envíos de formularios.
     */
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
