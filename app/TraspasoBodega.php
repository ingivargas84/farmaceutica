<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TraspasoBodega extends Model
{
    protected $table = 'traspasos_bodega';

    protected $fillable = [
        'cantidad',
        'bodega_origen',
        'bodega_destino',
        'producto_id',
        'user_id'
    ];

    public function bodegaOrigen(){
        return $this->belongsTo('App\Bodega', 'bodega_origen');
    }

    public function bodegaDestino(){
        return $this->belongsTo('App\Bodega', 'bodega_destino');
    }

    public function producto(){
        return $this->belongsTo('App\Producto', 'producto_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
