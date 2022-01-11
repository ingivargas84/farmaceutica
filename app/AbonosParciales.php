<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AbonosParciales extends Model
{
    protected $table = 'abonos_parciales';

    protected $fillable = [
        'id',
        'id_pedidos_maestro',
        'id_abonos',
        'id_notas',
    ];
}
