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
        Schema::create('attestations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_numero');
            $table->unsignedBigInteger('numero_attestation');
            $table->timestamps();

            $table->foreign('stock_numero')->references('id')->on('stocks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('numero_attestation')->references('id')->on('certificats')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attestations');
    }
};
