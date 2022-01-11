<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_factura');
            $table->string('serie_factura');
            $table->string('no_factura');
            $table->decimal('subtotal', 8, 2);
            $table->decimal('impuestos', 8, 2);
            $table->decimal('total', 8, 2);
            $table->text('motivo_anulacion')->nullable();

            $table->string('nit', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->string('nombre_factura')->nullable();

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
        Schema::dropIfExists('facturas');
    }
}
