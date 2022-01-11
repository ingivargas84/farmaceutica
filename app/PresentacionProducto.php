<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresentacionProducto extends Model
{
    protected $table = 'presentaciones_producto';

    protected $fillable = [
        'presentacion'
    ];

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }

    public function productos(){
        return $this->hasMany('App\Producto', 'presentacion', 'id');
    }
}
