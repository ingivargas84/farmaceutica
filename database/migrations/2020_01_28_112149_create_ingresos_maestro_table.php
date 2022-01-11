<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosMaestroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos_maestro', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_factura');
            $table->date('fecha_compra');
            $table->string('serie_factura');
            $table->string('num_factura');
            $table->decimal('total_ingreso', 8, 2);

            $table->unsignedInteger('bodega_id');
            $table->foreign('bodega_id')->references('id')->on('bodegas');
            
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->unsignedInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');

            $table->unsignedInteger('estado_ingreso')->default(1);
            $table->foreign('estado_ingreso')->references('id')->on('estados');

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
        Schema::dropIfExists('ingresos_maestro');
    }
}
