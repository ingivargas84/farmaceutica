<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoNotaEnvio extends Model
{
    protected $table = 'estados_nota_envio';

    protected $fillable = [
        'estado'
    ];

    public function notasEnvio(){
        return $this->hasMany('App\NotaEnvio', 'estado', 'id');
    }
}
