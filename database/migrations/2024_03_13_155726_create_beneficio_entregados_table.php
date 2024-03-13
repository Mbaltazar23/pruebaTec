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
        Schema::create('beneficio_entregados', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_beneficio');
            $table->unsignedInteger('id_user');
            $table->foreign('id_beneficio')->references('id')->on('beneficios')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->char('dv', 1);
            $table->decimal('total', 10, 2);
            $table->string('estado');
            $table->date('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficio_entregados');
    }
};
