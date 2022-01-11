<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ActualizacionBitacora
{
    public $modelo_id;
    public $user_id;
    public $accion;
    public $info_anterior;
    public $info_nueva;
    public $nombre_tabla;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($modelo_id, $user_id, $accion, $info_anterior, $info_nueva, $nombre_tabla)
    {
        $this->modelo_id = $modelo_id;
        $this->user_id = $user_id;
        $this->accion = $accion;
        $this->info_anterior = $info_anterior;
        $this->info_nueva = $info_nueva;
        $this->nombre_tabla = $nombre_tabla;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
