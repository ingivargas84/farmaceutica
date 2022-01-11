<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuentaCobrarDetalleAbono extends Model
{
    protected $table = 'cuentas_cobrar_detalle_abono';

    protected $fillable = [
        'fecha_ingreso',
        'monto',
        'cuentas_cobrar_maestro_id',
        'estado',
        'user_id',
        'forma_pago',
        'no_documento',
        'estado',
    ];

    public function cuentaCobrarMaestro(){
        return $this ->belongsTo('App\CuentaCobrarMaestro', 'cuentas_cobrar_maestro_id');
    }

    public function estado() {
        return $this->belongsTo('App\estados', 'estado', 'id');
    }

    public function formaPago(){
        return $this->belongsTo('App\FormaPago', 'forma_pago', 'id');
    }
}
