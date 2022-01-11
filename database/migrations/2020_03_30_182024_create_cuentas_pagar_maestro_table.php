<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentasPagarMaestroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_pagar_maestro', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_ingreso');
            $table->decimal('saldo', 8,2)->default(0);

            $table->unsignedInteger('id_proveedor');
            $table->foreign('id_proveedor')->references('id')->on('proveedores');

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
        Schema::dropIfExists('cuentas_pagar_maestro');
    }
}
