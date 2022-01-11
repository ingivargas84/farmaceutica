<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class PedidoMaestro extends Model
{
    protected $table = 'pedidos_maestro';

    protected $fillable = [
        'fecha_ingreso',
        'no_pedido',
        'total',
        'cliente_id',
        'user_id',
        'bodega_id',
        'estado',
        'estado_facturacion',
    ];

    //this function automagically inserts the current date when creating a model instance
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $dt = new DateTime();
            $model->fecha_ingreso = $dt->format('Y-m-d');
            return true;
        });
        static::created(function($model){
            $dt = new DateTime();
            $model->no_pedido = "P".$dt->format('Y')."-".str_pad($model->id, 5, '0',  STR_PAD_LEFT);
            $model->save();
        });
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }

    public function pedidosDetalle(){
        return $this->hasMany('App\PedidoDetalle', 'pedido_maestro_id');
    }

    public function cliente(){
        return $this->belongsTo('App\Cliente', 'cliente_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function factura(){
        return $this->hasOne('App\Factura', 'pedido_maestro_id', 'id');
    }

    public function notaEnvio(){
        return $this->hasOne('App\NotaEnvio', 'pedido_id', 'id');
    }

    public function bodega(){
        return $this->hasOne('App\Bodega', 'id');
    }

    public function cuentaCobrarDetallePedido(){
        return $this->belongsTo('App\CuentaCobrarDetallePedido', 'id');
    }

}
