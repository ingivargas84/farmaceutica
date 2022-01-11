<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class CuentaCobrarMaestro extends Model
{
    protected $table = 'cuentas_cobrar_maestro';

    protected $fillable = [
        'fecha_ingreso',
        'saldo',
        'id_cliente'
    ];

    public static function boot(){
        parent::boot();
        self::creating(function ($model){
            $dt = new DateTime();
            $model->fecha_ingreso = $dt->format('Y-m-d');
            return true;
        });
    }

    public function cuentasCobrarDetallePedido(){
        return $this->hasMany('App\CuentaCobrarDetallePedido', 'cuentas_cobrar_maestro_id', 'id');
    }

    public function cuentasCobrarDetalleAbono(){
        return $this->hasMany('App\CuentaCobrarDetalleAbono', 'cuentas_cobrar_maestro_id', 'id');
    }

    public function cliente(){
        return $this->belongsTo('App\Cliente', 'id_cliente', 'id');
    }
}
