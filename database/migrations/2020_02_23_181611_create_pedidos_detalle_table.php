<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad');
            $table->float('precio');
            $table->float('subtotal');

            $table->unsignedInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos');

            $table->unsignedInteger('estado')->default(1);
            $table->foreign('estado')->references('id')->on('estados');

            $table->unsignedInteger('pedido_maestro_id');
            $table->foreign('pedido_maestro_id')->references('id')->on('pedidos_maestro');

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
        Schema::dropIfExists('pedidos_detalle');
    }
}
