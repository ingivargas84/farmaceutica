<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidaAjusteDetalle extends Model
{
    protected $table = 'partidas_ajuste_detalle';

    protected $fillable = [
        'cantidad',
        'precio',
        'ingreso',
        'salida',
        'tipo_ajuste',
        'id_partida_ajuste_maestro',
        'id_producto',
        'estado'
    ];
}
