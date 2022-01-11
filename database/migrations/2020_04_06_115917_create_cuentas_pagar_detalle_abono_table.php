<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentasPagarDetalleAbonoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_pagar_detalle_abono', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_ingreso');
            $table->decimal('monto', 8, 2);
            $table->string('no_documento')->nullable();

            $table->unsignedInteger('cuentas_pagar_maestro_id');
            $table->foreign('cuentas_pagar_maestro_id')->references('id')->on('cuentas_pagar_maestro');

            $table->unsignedInteger('estado')->default(1);
            $table->foreign('estado')->references('id')->on('estados');

            $table->unsignedInteger('forma_pago');
            $table->foreign('forma_pago')->references('id')->on('formas_pago');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('cuentas_pagar_detalle_abono');
    }
}
