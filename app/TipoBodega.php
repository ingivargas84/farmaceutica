<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoBodega extends Model
{
    protected $table = 'tipos_bodega';

    protected $fillable = [
        'tipo'
    ];

    public function bodegas(){
        return $this->hasMany('App\Bodega', 'tipo', 'id');
    }
}
