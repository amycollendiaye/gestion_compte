<?php

use App\Models\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     protected $role;
     public function __construct($role)

     {
         $this->role=$role;
     }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
           $table->uuid('id')->primary();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->enum('role',["client","admin"]);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telephone')->unique();
            $table->string('adresse');
            $table->index(['id',"nom",'prenom','email','password','adresse','telephone']);
            $table->timestamps();


    });
}
 public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }


};
