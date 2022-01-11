<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacoras';

    protected $fillable = [
        'modelo_id',
        'user_id',
        'accion',
        'info_anterior',
        'info_nueva',
        'nombre_tabla'
    ];

}
