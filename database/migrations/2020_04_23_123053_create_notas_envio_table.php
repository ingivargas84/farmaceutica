<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasEnvioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas_envio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cliente')->required();
            $table->string('direccion')->required();
            $table->string('telefono')->required();
            $table->string('no_nota_envio')->nullable();

            $table->unsignedInteger('pedido_id');
            $table->foreign('pedido_id')->references('id')->on('pedidos_maestro');

            $table->unsignedInteger('estado_nota')->default(1);
            $table->foreign('estado_nota')->references('id')->on('estados_nota_envio');

            $table->unsignedInteger('estado')->default(1);
            $table->foreign('estado')->references('id')->on('estados');

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
        Schema::dropIfExists('notas_envio');
    }
}
