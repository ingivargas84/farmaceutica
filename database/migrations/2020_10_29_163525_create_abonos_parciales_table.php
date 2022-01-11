<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbonosParcialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonos_parciales', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('id_pedidos_maestro');
            $table->foreign('id_pedidos_maestro')->references('id')->on('pedidos_maestro');

            $table->unsignedInteger('id_abonos');
            $table->foreign('id_abonos')->references('id')->on('cuentas_cobrar_detalle_abono');

            $table->unsignedInteger('id_notas');
            $table->foreign('id_notas')->references('id')->on('notas_envio');


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
        Schema::dropIfExists('abonos_parciales');
    }
}
