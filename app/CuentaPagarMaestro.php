<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class CuentaPagarMaestro extends Model
{
    protected $table = 'cuentas_pagar_maestro';

    protected $fillable = [
        'fecha_ingreso',
        'saldo',
        'id_proveedor',
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

    public function cuentasPagarDetalleCompra(){
        return $this->hasMany('App\CuentaPagarDetalleCompra', 'cuentas_pagar_maestro_id', 'id');
    }

    public function cuentasPagarDetalleAbono(){
        return $this->hasMany('App\CuentaPagarDetalleAbono', 'cuentas_pagar_maestro_id', 'id');
    }
}
