<?php

namespace App\Models;

use Database\Factories\DistrictFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    /** @use HasFactory<DistrictFactory> */
    use HasFactory;

    /**
     * Relación muchos a uno: Un distrito pertenece a una provincia.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
