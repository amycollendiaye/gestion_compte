<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str; // ✅ <--- ajoute cette ligne

class User extends Authenticatable implements JWTSubject
{
use HasApiTokens, HasFactory, Notifiable;

    // Use UUIDs for primary key
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'adresse',
        'telephone',
        'email',
        'password',
        'role'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    //   public function isAdmin(): bool
    // {
    //     return $this->role === 'admin';
    // }
    public function isAdmin(): bool
{
    return $this->admin()->exists(); // Vérifie si l'utilisateur a un enregistrement admin
}

public function isClient(): bool
{
    return $this->client()->exists(); // Vérifie si l'utilisateur a un enregistrement client
}

    public function client()
    {
        return $this->hasOne(Client::class);
    }
      public function admin()
    {
        return $this->hasOne(Admin::class);
    }
     
    //  public function isClient(): bool
    // {
    //     return $this->role === 'client';
    // }
protected static function booted()
{
    static::creating(function ($user) {
        if (!$user->id) {
            $user->id = Str::uuid();
        }
    });

}

/**
 * Get the identifier that will be stored in the subject claim of the JWT.
 *
 * @return mixed
 */
public function getJWTIdentifier()
{
    return $this->getKey();
}

/**
 * Return a key value array, containing any custom claims to be added to the JWT.
 *
 * @return array
 */
public function getJWTCustomClaims()
{
    return [];
}


}
