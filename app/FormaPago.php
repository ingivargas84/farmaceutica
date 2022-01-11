<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = 'formas_pago';

    protected $fillable = [
        'nombre'
    ];

    public function estado()
    {
        return $this->belongsTo('App\estados', 'estado');
    }

    public function cuentasPagarDetalleAbono(){
        return $this->hasMany('App\CuentaPagarDetalleAbono', 'forma_pago', 'id');
    }
}
