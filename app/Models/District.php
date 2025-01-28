<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Province;

class District extends Model
{
    /** @use HasFactory<\Database\Factories\DistrictFactory> */
    use HasFactory;

    /**
     * Relación muchos a uno: Un distrito pertenece a una provincia.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
