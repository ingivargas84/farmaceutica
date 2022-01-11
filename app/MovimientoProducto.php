<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class MovimientoProducto extends Model
{
    protected $table = 'movimientos_producto';

    protected $fillable = [
        'fecha_ingreso',
        'existencias',
        'caducidad',
        'bodega_id',
        'precio_compra',
        'producto_id'
    ];

    public static function boot(){
        parent::boot();
        self::creating(function($model){
            $dt = new DateTime;
            $model->fecha_ingreso = $dt->format('y-m-d');
            return true;
        });
    }

    public function producto(){
        return $this->belongsTo('App\Producto', 'producto_id');
    }

    public function bodega()
    {
        return $this->belongsTo('App\Bodega', 'bodega_id');
    }


    public function ingresosDetalle(){
        return $this->hasMany('App\IngresoDetalle', 'movimiento_producto_id', 'id');
    }
}
