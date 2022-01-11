<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $table = 'visitas';

    protected $fillable = [
        'cliente_id',
        'nombre_cliente',
        'telefono_cliente',
        'direccion_cliente',
        'observaciones',
        'user_id',
        'estado',
        'fecha',
    ];

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }

    public function cliente(){
        return $this->belongsTo('App\Cliente', 'cliente_id');
    }


}
