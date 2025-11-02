<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
     // Use UUIDs for primary key
    public $incrementing = false;
    protected $keyType = 'string';
    
     protected $fillable = [
        'id',
        'user_id',
        'matricule'
    ];
      public function user()
    {
        return $this->belongsTo(User::class);
    }
}
