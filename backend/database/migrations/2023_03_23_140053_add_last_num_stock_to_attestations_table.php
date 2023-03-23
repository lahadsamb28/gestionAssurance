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
        Schema::table('attestations', function (Blueprint $table) {
            //
            $table->bigInteger('dernier_numero_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attestations', function (Blueprint $table) {
            //
            $table->dropColumn('dernier_numero_stock');
        });
    }
};
