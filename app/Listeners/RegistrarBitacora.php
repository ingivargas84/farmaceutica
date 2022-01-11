<?php

namespace App\Listeners;

use App\Events\ActualizacionBitacora;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Bitacora;
use Carbon\Carbon;
class RegistrarBitacora
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActualizacionBitacora  $event
     * @return void
     */
    public function handle(ActualizacionBitacora $event)
    {
        $bitacora = new Bitacora;
        $bitacora->modelo_id = $event->modelo_id;
        $bitacora->user_id = $event->user_id;
        $bitacora->accion = $event->accion;
        $bitacora->info_anterior = $event->info_anterior;
        $bitacora->info_nueva =  $event->info_nueva;
        $bitacora->nombre_tabla = $event->nombre_tabla;
        $bitacora->save();
    }
}
