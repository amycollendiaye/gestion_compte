<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;
    // Use UUID for primary key
    public $incrementing = false;
    protected $keyType = 'string';
     protected $fillable=[
        
        'numero_compte',
        'type_compte',
        'statut',
        'archive',
        'client_id',
     ];
      public function client()
     {
        return $this->belongsTo(Client::class);
     }

    // Mutator for numero_compte
     public function setNumeroCompteAttribute($value)
    {
        // Si vide, génère un numéro aléatoire
        if (empty($value)) {
            $this->attributes['numero_compte'] = 'ORANGEBANK-' . mt_rand(1000000000, 9999999999);
        } else {
            // Supprime les espaces et ajoute le préfixe SN
            $this->attributes['numero_compte'] = 'ORANGEBANK-' . str_replace(' ', '', $value);
        }
    }
}
