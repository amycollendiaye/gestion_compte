<?php

namespace App\Models;

use App\Models\Scopes\NonSuppCompte;
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
        "motif_blocage"
     ];
       protected static function booted()
    {
        static::addGlobalScope(new NonSuppCompte);
    }
     public function scopeNumero($query, string $numero)
    {
        return $query->where('numero_compte', $numero);
    }
   public function scopeTelephone($query, string $telephone)
    {
        
        return $query->whereHas('client.user', function ($q) use ($telephone) {
        $q->where('telephone', $telephone);
    });

        }

        const CREATED_AT = 'dateCreation';
    const UPDATED_AT = 'derniereModification';
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
