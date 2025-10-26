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
             $table->enum('type_compte',['epargne','cheque','courant'])->default('courant');
             $table->enum('statut',['actif','inactif','bloque'])->default('actif'); 
             $table->uuid('client_id');
             $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
             $table->enum("archive",['supprime','non_supprime']);
             $table->timestamp('dateCreation')->useCurrent();
            $table->timestamp('derniereModification')->useCurrent()->useCurrentOnUpdate();
             $table->string('motif_blocage')->nullable();
            $table->index(['numero_compte',"client_id","statut","type_compte",'archive']);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
