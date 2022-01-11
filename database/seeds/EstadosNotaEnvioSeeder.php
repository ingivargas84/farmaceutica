<?php

use App\EstadoNotaEnvio;
use Illuminate\Database\Seeder;

class EstadosNotaEnvioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $ene = new EstadoNotaEnvio();
        $ene->estado = 'Creada';
        $ene->save();

        $ene = new EstadoNotaEnvio();
        $ene->estado = 'Enviada';
        $ene->save();

        $ene = new EstadoNotaEnvio();
        $ene->estado = 'Recibida';
        $ene->save();

        $ene = new EstadoNotaEnvio();
        $ene->estado = 'Rechazada';
        $ene->save();
    }
}
