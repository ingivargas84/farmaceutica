<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTraspasosBodegaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traspasos_bodega', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad');

            $table->unsignedInteger('bodega_origen');
            $table->foreign('bodega_origen')->references('id')->on('bodegas');

            $table->unsignedInteger('bodega_destino');
            $table->foreign('bodega_destino')->references('id')->on('bodegas');

            $table->unsignedInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos');

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
        Schema::dropIfExists('traspasos_bodega');
    }
}
