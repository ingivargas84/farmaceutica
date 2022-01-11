<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'nombre_comercial',
        'nombre_generico',
        'concentracion',
        'precio_venta',
        'stock_maximo',
        'stock_minimo',
        'estado',
        'presentacion'
    ];

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }

    public function presentacion(){
        return $this->belongsTo('App\PresentacionProducto', 'presentacion');
    }

    public function movimientosProducto(){
        return $this->hasMany('App\MovimientoProducto', 'producto_id', 'id');
    }

    public function ingresosDetalle(){
        return $this->hasMany('App\IngresoDetalle', 'producto_id', 'id');
    }

    public function pedidosDetalle(){
        return $this->hasMany('App\PedidoDetalle', 'producto_id', 'id');
    }
}
