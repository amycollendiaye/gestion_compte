<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
       // Use UUID for primary key
    public $incrementing = false;
    protected $keyType = 'string';
    use HasFactory;
     protected $fillables=[
            'compte_id',
            'type_transaction',
            'devise',
            'description',
            'date_transaction',
            'statut',
    ];
     public function client()
     {
         return $this->belongsTo(Client::class);
     }
}
