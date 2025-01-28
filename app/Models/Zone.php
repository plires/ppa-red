<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Region;

class Zone extends Model
{
    /** @use HasFactory<\Database\Factories\ZoneFactory> */
    use HasFactory;

    /**
     * Relación muchos a uno: Una Zona pertenece a una region.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
