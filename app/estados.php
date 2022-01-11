<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class estados extends Model
{
    protected $table = 'estados';

    protected $fillable = [
        'id',
        'estado'
    ];

    public function clientes(){
        return $this->hasMany('App\Cliente', 'estado', 'id');
    }

    public function bodegas(){
        return $this->hasMany('App\Bodega', 'estado', 'id');
    }

    public function formasPago(){
        return $this->hasMany('App\FormaPago', 'estado', 'id');
    }

    public function presentacionesProducto(){
        return $this->hasMany('App\PresentacionProducto', 'estado', 'id');
    }

    public function productos(){
        return $this->hasMany('App\Producto', 'estado', 'id');
    }

    public function ingresosMaestro(){
        return $this->hasMany('App\IngresoMaestro', 'estado_ingreso', 'id');
    }

    public function ingresosDetalle(){
        return $this->hasMany('App\IngresoDetalle', 'estado', 'id');
    }

    public function pedidosDetalle(){
        return $this->hasMany('App\PedidoMaestro', 'estado', 'id');
    }

    public function cuentasPagarDetalleCompra(){
        return $this->hasMany('App\CuentaPagarDetalleCompra', 'estado', 'id');
    }

    public function cuentasPagarDetalleAbono(){
        return $this->hasMany('App\CuentaPagarDetalleAbono', 'estado', 'id');
    }

    public function notasEnvio(){
        return $this->hasMany('App\NotaEnvio', 'estado', 'id');
    }

    public function territorios(){
        return $this->hasMany('App\territorios', 'estado', 'id');
    }
}
