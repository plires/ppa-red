<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use App\Models\Region;
use App\Models\Locality;
use App\Models\FormSubmission;

class Province extends Model
{
    /** @use HasFactory<\Database\Factories\ProvinceFactory> */
    use HasFactory;

    /**
     * Relación uno a muchos: Una provincia tiene muchos distritos.
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    /**
     * Relación uno a muchos: Una provincia tiene muchas regiones.
     */
    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    /**
     * Relación uno a muchos: Una provincia tiene muchas localidades.
     */
    public function localities()
    {
        return $this->hasMany(Locality::class);
    }

    /**
     * Relación uno a muchos: Una provincia tiene muchos envíos de formularios.
     */
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
