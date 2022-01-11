<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentasCobrarDetallePedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_cobrar_detalle_pedido', function (Blueprint $table) {
            $table->increments('id');

            $table->date('fecha_ingreso');

            $table->unsignedInteger('pedido_id');
            $table->foreign('pedido_id')->references('id')->on('pedidos_maestro');

            $table->unsignedInteger('cuentas_cobrar_maestro_id');
            $table->foreign('cuentas_cobrar_maestro_id')->references('id')->on('cuentas_cobrar_maestro');

            $table->unsignedInteger('estado')->default('1');
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
        Schema::dropIfExists('cuentas_cobrar_detalle_pedido');
    }
}
