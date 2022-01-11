<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartidasAjusteDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidas_ajuste_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad');
            $table->decimal('precio', 8, 2);
            $table->decimal('ingreso', 8, 2)->default(0);
            $table->decimal('salida', 8, 2)->default(0);
            $table->integer('tipo_ajuste');

            $table->unsignedInteger('id_partida_ajuste_maestro');
            $table->foreign('id_partida_ajuste_maestro')->references('id')->on('partidas_ajuste_maestro');

            $table->unsignedInteger('id_producto');
            $table->foreign('id_producto')->references('id')->on('productos');

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
        Schema::dropIfExists('partidas_ajuste_detalle');
    }
}
