<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('territorios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('territorio');
            $table->string('descripcion', 200);


            $table->unsignedInteger('estado')->default(1);
            $table->foreign('estado')->references('id')->on('estados');

            $table->unsignedInteger('user')->nullable();
            $table->foreign('user')->references('id')->on('users');
            
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
        Schema::dropIfExists('territorios');
    }
}
