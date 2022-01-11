<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartidasAjusteMaestroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidas_ajuste_maestro', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo_partida')->nullable();
            $table->date('fecha_ingreso');
            $table->decimal('total_ingreso', 8, 2)->default(0);
            $table->decimal('total_salida', 8, 2)->default(0);
            $table->decimal('saldo', 8, 2);
            
            $table->unsignedInteger('id_bodega');
            $table->foreign('id_bodega')->references('id')->on('bodegas');

            $table->unsignedInteger('estado')->default(1);
            $table->foreign('estado')->references('id')->on('estados');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partidas_ajuste_maestro');
    }
}
