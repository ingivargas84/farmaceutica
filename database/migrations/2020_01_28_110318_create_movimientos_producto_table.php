<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos_producto', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_ingreso');
            $table->integer('existencias');
            $table->date('caducidad');
            $table->decimal('precio_compra', 8, 2);

            $table->unsignedInteger('bodega_id');
            $table->foreign('bodega_id')->references('id')->on('bodegas');

            $table->unsignedInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos');
            
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
        Schema::dropIfExists('movimientos_producto');
    }
}
