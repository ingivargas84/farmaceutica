<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nit', 20)->nullable();
            $table->string('nombre_cliente')->required();
            $table->integer('dias_credito')->required();
            $table->string('encargado_compras')->nullable();
            $table->date('nacimiento_compras')->nullable();
            $table->string('telefono_compras', 30)->nullable();
            $table->string('encargado_paga')->nullable();
            $table->date('nacimiento_paga')->nullable();
            $table->string('telefono_paga', 30)->nullable();
            $table->string('direccion')->nullable();
            $table->string('email')->nullable();

            $table->unsignedInteger('territorio')->nullable();
            $table->foreign('territorio')->references('id')->on('territorios')->onDelete('cascade');

            $table->unsignedInteger('estado')->default(1);
            $table->foreign('estado')->references('id')->on('estados')->onDelete('cascade');

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
        Schema::dropIfExists('clientes');
    }
}
