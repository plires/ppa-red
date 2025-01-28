<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Province;
use App\Models\Zone;

class Region extends Model
{
    /** @use HasFactory<\Database\Factories\RegionFactory> */
    use HasFactory;

    /**
     * Relación muchos a uno: Una Region pertenece a una provincia.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Relación uno a muchos: Una Region tiene muchas zonas.
     */
    public function zones()
    {
        return $this->hasMany(Zone::class);
    }
}
