<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comptes', function (Blueprint $table) {
            $table->uuid('id')->primary();
             $table->string('numero_compte')->unique();
             $table->enum('type_compte',['epargne','cheque',]);
             $table->enum('statut',['actif','ferme','bloque'])->default('actif'); 
             $table->uuid('client_id');
            $table->decimal('solde', 15, 2)->default(0);
             $table->string('devise')->default('XOF');
             $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
             $table->timestamp('dateCreation')->useCurrent();
            $table->timestamp('derniereModification')->useCurrent()->useCurrentOnUpdate();
             $table->string('motif_blocage')->nullable();
            $table->index(['numero_compte',"client_id","statut","type_compte"]);
               $table->softDeletes();


               

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
