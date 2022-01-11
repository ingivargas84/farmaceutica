<?php

use Illuminate\Database\Seeder;
use App\Negocio;

class NegocioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $negocio = new Negocio();
        $negocio->save();

    }
}
