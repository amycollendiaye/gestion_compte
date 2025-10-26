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
     public function comptes()
    {
        return $this->hasMany(Compte::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
