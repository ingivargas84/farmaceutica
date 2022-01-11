<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentasCobrarMaestroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_cobrar_maestro', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_ingreso');
            $table->decimal('saldo', 8, 2)->default(0);

            $table->unsignedInteger('id_cliente');
            $table->foreign('id_cliente')->references('id')->on('clientes');
            
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
        Schema::dropIfExists('cuentas_cobrar_maestro');
    }
}
