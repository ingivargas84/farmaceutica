<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table = 'bodegas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'user_id',
    ];

    public function tipo(){
        return $this->belongsTo('App\TipoBodega', 'tipo');
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }

    public function movimientosProducto(){
        return $this->hasMany('App\movimientoProducto', 'bodega_id', 'id');
    }

    public function pedidosMaestro(){
        return $this->hasMany('App\PedidoMaestro', 'bodega_id', 'id');
    }

    public function ingresosMaestro(){
        return $this->hasMany('App\IngresoMaestro', 'bodega_id', 'id');
    }
}
