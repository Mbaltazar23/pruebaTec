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
        //creo el esquema de la tabla con su id incrementable y respetando los demas campos
        Schema::create('monto_maximos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_beneficio');
            $table->foreign('id_beneficio')->references('id')->on('beneficios')->onDelete('cascade');
            $table->decimal('monto_minimo', 10, 2);
            $table->decimal('monto_maximo', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monto_maximos');
    }
};
