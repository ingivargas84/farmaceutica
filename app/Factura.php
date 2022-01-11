<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'fecha_factura',
        'serie_factura',
        'no_factura',
        'subtotal',
        'impuestos',
        'total',
        'motivo_anulacion',
        'estado',
        'pedido_maestro_id',
        'nit',
        'direccion',
        'nombre_factura',
    ];

    //this function automagically inserts the current date when creating a model instance
    /*public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $dt = new DateTime();
            $model->fecha_factura = $dt->format('y-m-d');
            return true;
        });
    }*/

    public function pedidoMaestro(){
        return $this->belongsTo('App\PedidoMaestro', 'pedido_maestro_id', 'id');
    }

}
