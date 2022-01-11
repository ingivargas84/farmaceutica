<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'id',
        'nombre_cliente',
        'dias_credito',
        'nit',
        'encargado_compras',
        'nacimiento_compras',
        'telefono_compras',
        'encargado_paga',
        'nacimiento_paga',
        'telefono_paga',
        'direccion',
        'email',
        'territorio',
        'estado'
    ];

    public function territorio(){
        return $this->belongsTo('App\territorios', 'territorio');
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }

    public function pedidosMaestro(){

        return $this->hasMany('App\PedidoMaestro', 'cliente_id', 'id');
    }

    public function cuentaCobrarMaestro(){
        return $this->hasOne('App\CuentaCobrarMaestro', 'id_cliente', 'id');
    }
}
