<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class CuentaPagarDetalleCompra extends Model
{
    protected $table = 'cuentas_pagar_detalle_compra';

    protected $fillable = [
        'fecha_ingreso',
        'ingreso_id',
        'cuentas_pagar_maestro_id',
        'estado',
        'pago_factura',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $dt = new DateTime;
            $model->fecha_ingreso = $dt->format('Y-m-d');
            return true;
        });
    }

    public function ingreso(){
        return $this->hasOne('App\IngresoMaestro', 'id');
    }

    public function cuentaPagarMaestro(){
        return $this->belongsTo('App\CuentaPagarMaestro', 'cuentas_pagar_maestro_id');
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado', 'id');
    }

    public function monto(){
        return $this->ingreso->total_ingreso;
    }
}
