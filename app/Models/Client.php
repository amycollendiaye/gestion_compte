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
}
