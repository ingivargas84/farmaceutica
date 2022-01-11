<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNegocioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('negocio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nit', 20)->nullable()->default(null);
            $table->string('nombre_contable')->nullable()->default(null);
            $table->string('nombre_comercial')->nullable()->default(null);
            $table->string('direccion')->nullable()->default(null);
            $table->string('telefonos')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->LONGTEXT('logotipo')->nullable()->default(null);
            $table->date('fecha_inicio')->nullable()->default(null);
            $table->string('no_patente')->nullable()->default(null);
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
        Schema::dropIfExists('negocio');
    }
}
