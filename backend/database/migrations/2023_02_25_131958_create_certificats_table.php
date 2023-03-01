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
        Schema::create('certificats', function (Blueprint $table) {
            $table->id();
            $table->enum('typeCertificat', ['vip', 'premium', 'standard']);
            $table->timestamp('date_delivrance');
            $table->timestamp('date_expiration');
            $table->unsignedBigInteger('stock');
            $table->unsignedBigInteger('proprietaire');
            $table->unsignedBigInteger('vehicule');
            $table->timestamps();

            $table->foreign('stock')->references('id')->on('stocks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('proprietaire')->references('id')->on('proprietaires')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vehicule')->references('id')->on('vehicules')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificats');
    }
};
