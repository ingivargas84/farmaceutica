<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class CuentaCobrarDetallePedido extends Model
{
    protected $table = 'cuentas_cobrar_detalle_pedido';

    protected $fillable = [
        'fecha_ingreso',
        'pedido_id',
        'cuentas_cobrar_maestro_id',
        'estado'
    ];

    public static function boot() {
        parent::boot();
        self::creating(function ($model){
            $dt = new DateTime();
            $model->fecha_ingreso = $dt->format('Y-m-d');
        });
    }

    public function pedido(){
        return $this->hasOne('App\PedidoMaestro', 'id');
    }

    public function cuentaCobrarMaestro(){
        return $this->belongsTo('App\CuentaCobrarMaestro', 'cuentas_cobrar_maestro_id');
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado', 'id');
    }

    public function monto(){
        return $this->pedido->total;
    }
}
