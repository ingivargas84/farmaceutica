<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaEnvio extends Model
{
    protected $table = 'notas_envio';

    protected $fillable = [
        'cliente',
        'direccion',
        'telefono',
        'estado_nota',
        'estado',
        'no_nota_envio',
        'pedido_id'
    ];
    //this function automagically inserts the current date when creating a model instance
    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->no_nota_envio = "NE".$model->created_at->format('Y')."-".str_pad($model->id, 5, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }

    public function estadoNota(){
        return $this->belongsTo('App\EstadoNotaEnvio', 'estado_nota');
    }

    public function pedido(){
        return $this->belongsTo('App\PedidoMaestro', 'pedido_id');
    }
}
