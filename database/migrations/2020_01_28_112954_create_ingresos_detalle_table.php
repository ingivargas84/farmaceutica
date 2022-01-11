<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_ingreso');
            $table->decimal('precio_compra', 8,2);
            $table->integer('cantidad');
            $table->decimal('subtotal', 8, 2);

            $table->unsignedInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos');

            $table->unsignedInteger('estado')->default(1);
            $table->foreign('estado')->references('id')->on('estados');

            $table->unsignedInteger('ingreso_maestro_id');
            $table->foreign('ingreso_maestro_id')->references('id')->on('ingresos_maestro');

            $table->unsignedInteger('movimiento_producto_id');
            $table->foreign('movimiento_producto_id')->references('id')->on('movimientos_producto');

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
        Schema::dropIfExists('ingresos_detalle');
    }
}
