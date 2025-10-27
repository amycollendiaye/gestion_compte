<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    // Use UUIDs for primary key
    public $incrementing = false;
    protected $keyType = 'string';
    
     protected $fillable = [
        'user_id',
        'cni'


    ];
       protected static function booted()
    {
        static::creating(function ($client) {
            if ($client->user) {
                $client->user->role = 'client';
                $client->user->save();
            }
        });
    }
     public function comptes()
    {
        return $this->hasMany(Compte::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

}
