<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class CuentaPagarDetalleAbono extends Model
{
    protected $table = 'cuentas_pagar_detalle_abono';

    protected  $fillable = [
        'fecha_ingreso',
        'monto',
        'cuentas_pagar_maestro_id',
        'estado',
        'forma_pago',
        'no_documento',
        'user_id'
    ];

    public function cuentaPagarMaestro()
    {
        return $this->belongsTo('App\CuentaPagarMaestro', 'cuentas_pagar_maestro_id');
    }

    public function estado()
    {
        return $this->belongsTo('App\estados', 'estado', 'id');
    }

    public function formaPago()
    {
        return $this->belongsTo('App\FormaPago', 'forma_pago', 'id');
    }

}
