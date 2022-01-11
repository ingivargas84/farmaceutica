<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Negocio extends Model
{
    protected $table = 'negocio';
    protected $dates = ['fecha_inicio'];

    protected $fillable = [
        'nit',
        'nombre_contable',
        'nombre_comercial',
        'direccion',
        'telefonos',
        'email',
        'logotipo',
        'fecha_inicio',
        'no_patente',
    ];

    public function setFechaInicioAttribute($fecha_inicio)
    {
        $this->attributes['fecha_inicio'] = $fecha_inicio ? Carbon::parse($fecha_inicio) :null;
    }
}
