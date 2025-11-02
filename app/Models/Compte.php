<?php

namespace App\Models;

use App\Models\Scopes\NonSuppCompte;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compte extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];
    // Use UUID for primary key
    public $incrementing = false;
    protected $keyType = 'string';
     protected $fillable=[
            'id',
        'numero_compte',
        'type_compte',
        'statut',
        'client_id',
        'solde',
        'devise'
     ];
         const CREATED_AT = 'dateCreation';
    const UPDATED_AT = 'derniereModification';


      public function client()
     {
        return $this->belongsTo(Client::class);
     }

    // Mutator for numero_compte
    public function setNumeroCompteAttribute($value)
    {
        if (empty($value) || $value === '') {
            // Génération d'un numéro unique
            do {
                $numero = 'ORANGEBANK-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            } while (static::where('numero_compte', $numero)->exists());
            
            $this->attributes['numero_compte'] = $numero;
        } else {
            $this->attributes['numero_compte'] = 'ORANGEBANK-' . str_replace(' ', '', $value);
        }
    }
          protected static function booted()
    {
        static::addGlobalScope(new NonSuppCompte);
    
    }
}
