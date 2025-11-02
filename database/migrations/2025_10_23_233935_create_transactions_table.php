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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('compte_id'); 
            $table->enum('type_transaction', ['depot', 'retrait','virement','frais']);
            $table->decimal('montant', 15, 2); 
            $table->string('devise', 10)->default('XOF');
            $table->text('description')->nullable();
            $table->dateTime('date_transaction');
        $table->enum('statut', ['en_attente', 'validee', 'annulee'])->default('en_attente');
            $table->timestamps();

            
            $table->foreign('compte_id')->references('id')->on('comptes')->onDelete('cascade');
            $table->index(["compte_id","type_transaction","montant","devise",'description','date_transaction',"statut"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
