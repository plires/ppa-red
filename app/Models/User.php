<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Locality;
use Illuminate\Support\Str;
use App\Models\FormSubmission;
use App\Models\FormResponse;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ADMIN_USER = 'admin';
    const PARTNER_USER = 'partner';

    const UNVERIFIED_USER = NULL;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'email_verified_at',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin()
    {
        return $this->role == User::ADMIN_USER;
    }

    public function isPartner()
    {
        return $this->role == User::PARTNER_USER;
    }

    public static function generateVerificationToken()
    {
        return Str::random(40);
    }

    /**
     * Relación uno a muchos: Un usuario puede tener varios envíos de formularios.
     */
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function formResponses()
    {
        return $this->hasMany(FormResponse::class);
    }

    /**
     * Relación uno a muchos: Un usuario puede tener varias localidades
     */
    public function localities()
    {
        return $this->hasMany(Locality::class, 'user_id'); // Especificar la clave foránea correcta
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
