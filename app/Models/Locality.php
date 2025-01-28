<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Partner;
use App\Models\Province;

class Locality extends Model
{
    /** @use HasFactory<\Database\Factories\LocalityFactory> */
    use HasFactory;

    public function partner()
    {
        return $this->belongsToMany(Partner::class, 'partner_locality');
    }

    /**
     * Relación muchos a uno: Una Region pertenece a una provincia.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
