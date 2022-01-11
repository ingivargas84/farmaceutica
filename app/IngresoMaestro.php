<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngresoMaestro extends Model
{
    protected $table = 'ingresos_maestro';

    protected $fillable = [
        'fecha_factura',
        'fecha_compra',
        'serie_factura',
        'num_factura',
        'total_ingreso',
        'user_id',
        'bodega_id',
        'proveedor_id',
        'estado_ingreso'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function proveedor(){
        return $this->belongsTo('App\Proveedor', 'proveedor_id');
    }

    public function bodega(){
        return $this->belongsTo('App\Bodega', 'bodega_id');
    }

    public function estadoIngreso(){
        return $this->belongsTo('App\estados', 'estado_ingreso');
    }

    public function ingresosDetalle(){
        return $this->hasMany('App\IngresoDetalle', 'ingreso_maestro_id', 'id');
    }

    public function cuentaPagarDetalleCompra(){
        return $this->hasOne('App\CuentaPagarDetalleCompra', 'ingreso_id', 'id');
    }
}
