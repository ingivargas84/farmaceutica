<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    protected $table = 'pedidos_detalle';

    protected $fillable = [
        'cantidad',
        'precio',
        'subtotal',
        'producto_id',
        'estado',
        'pedido_maestro_id'
    ];

    public function producto(){
        return $this->belongsTo('App\Producto', 'producto_id');
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }

    public function pedidoMaestro(){
        return $this->belongsTo('App\PedidoMaestro', 'pedido_maestro_id');
    }
}
