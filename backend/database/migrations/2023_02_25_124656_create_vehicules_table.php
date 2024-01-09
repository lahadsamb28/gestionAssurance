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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->enum('typeDeVehicule', ['Vehicule Particulier','Camionnette','Tricycle','Quadricycle','Transport en commun','Moto','Tracteur routier']);
            $table->string('immatriculation');
            $table->enum('categorie', ['coupe','berline','hayons','break','limousines','pick-up','crossovers','SUV','fourgonnettes','mini-fourgonnettes','carrosserie liftback','cabriolets','minibus','roadsters','targa']);
            $table->string('marque');
            $table->string('model');
            $table->year('annee');
            $table->enum('transmission', ['automatique', 'manuel']);
            $table->enum('energie', ['gasoil', 'essence', 'electrique', 'hybride']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
