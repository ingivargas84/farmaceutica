<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentasPagarDetalleCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_pagar_detalle_compra', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_ingreso');
            $table->integer('pago_factura')->default(1);
            $table->unsignedInteger('ingreso_id');
            $table->foreign('ingreso_id')->references('id')->on('ingresos_maestro');

            $table->unsignedInteger('cuentas_pagar_maestro_id');
            $table->foreign('cuentas_pagar_maestro_id')->references('id')->on('cuentas_pagar_maestro');

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
        Schema::dropIfExists('cuentas_pagar_detalle_compra');
    }
}
