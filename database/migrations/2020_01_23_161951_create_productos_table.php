<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->required();
            $table->string('nombre_comercial')->required();
            $table->string('nombre_generico')->required();
            $table->string('concentracion')->required();
            $table->decimal('precio_venta', 8, 2)->required();
            $table->integer('stock_maximo')->required();
            $table->integer('stock_minimo')->required();

            $table->unsignedInteger('estado')->default(1)->required();
            $table->foreign('estado')->references('id')->on('estados');

            $table->unsignedInteger('presentacion')->required();
            $table->foreign('presentacion')->references('id')->on('presentaciones_producto');

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
        Schema::dropIfExists('productos');
    }
}
