<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class IngresoDetalle extends Model
{
    protected $table = 'ingresos_detalle';

    protected  $fillable = [
        'fecha_ingreso',
        'precio_compra',
        'cantidad',
        'producto_id',
        'estado',
        'subtotal',
        'ingreso_maestro_id',
        'movimiento_producto_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $dt = new DateTime;
            $model->fecha_ingreso = $dt->format('y-m-d');
            return true;
        });
    }

    public function producto(){
        return $this->belongsTo('App\Producto', 'producto_id');
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }

    public function ingresoMaestro(){
        return $this->belongsTo('App\IngresoMaestro', 'ingreso_maestro_id');
    }

    public function movimientoProducto(){
        return $this->belongsTo('App\MovimientoProducto', 'movimiento_producto_id');
    }
}
