<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Zone;
use App\Models\Partner;
use App\Models\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Locality extends Model
{
    /** @use HasFactory<\Database\Factories\LocalityFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'zone_id', 'province_id', 'user_id'];

    /**
     * Relación muchos a uno: Una localidad pertenece a un socio.
     */
    public function partner()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación muchos a uno: Una localidad pertenece a un usuario.
     */
    public function user()
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

    /**
     * Relación uno a muchos: Una localidad tiene muchos envíos de formularios.
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
