<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class territorios extends Model
{
    protected $table = 'territorios';

    protected $fillable = [
        'id',
        'territorio',
        'descripcion',
        'estado',
        'user',
    ];

    public function clientes(){
        return $this->hasMany('App\Cliente', 'territorio','id');
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }
}
