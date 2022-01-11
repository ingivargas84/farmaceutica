<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstadoCuentaProveedor extends Model
{
    protected $table = 'estados_cuenta_proveedor';

    protected $fillable = [
        'documento_id',
        'tipo_transaccion',
        'proveedor_id',
        'estado'
    ];

    public function proveedor(){
        return $this->BelongsTo('App\Proveedor', 'proveedor_id');
    }
}
