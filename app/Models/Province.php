<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Zone;
use App\Models\Locality;
use App\Models\FormSubmission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Province extends Model
{
    /** @use HasFactory<\Database\Factories\ProvinceFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

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

    public function getFormattedModifiedDateAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->format('d/m/y');
    }

    public function getFormattedDeletedDateAttribute()
    {
        return Carbon::parse($this->attributes['deleted_at'])->format('d/m/y');
    }
}
