<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
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
        return $this->belongsTo(User::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
