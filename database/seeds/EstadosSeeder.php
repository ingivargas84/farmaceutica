<?php

use Illuminate\Database\Seeder;
use App\estados;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $edo = new estados();
        $edo->estado = 'Activo';
        $edo->save();

        $edo = new estados();
        $edo->estado = 'Inactivo';
        $edo->save();
    }
}
