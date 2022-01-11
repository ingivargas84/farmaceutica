<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'id',
        'nombre_comercial',
        'nombre_legal',
        'nit',
        'direccion',
        'telefono',
        'email',
        'contacto1',
        'contacto2',
        'estado',
        'user_id',
        'dias_credito',
    ];

    public function ingresosMaestro(){
        return $this->hasMany('App\IngresoMaestro', 'proveedor_id', 'id');
    }

    public function estadoCuentaProveedor(){
        return $this->hasMany('App\EstadoCuentaProveedor', 'proveedor_id', 'id');
    }
}
