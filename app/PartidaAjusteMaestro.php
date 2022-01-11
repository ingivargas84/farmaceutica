<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class PartidaAjusteMaestro extends Model
{
    protected $table = 'partidas_ajuste_maestro';

    protected $fillable =[
        'codigo_partida',
        'fecha_ingreso',
        'total_ingreso',
        'total_salida',
        'saldo',
        'id_bodega',
        'estado'
    ];

    public static function boot(){
        parent::boot();
        self::creating(function($model){
            $dt = new DateTime();
            $model->fecha_ingreso = $dt->format('Y-m-d');
            return true;
        });
        static::created(function($model){
            $dt = new DateTime();
            $model->codigo_partida = "PA".$dt->format('Y')."-".str_pad($model->id, 5, "0", STR_PAD_LEFT);
            $model->save();
        });
    }
    
    public function partidasAjusteDetalle(){
        return $this->hasMany('App\PartidaAjusteDetalle', 'id_partida_ajuste_maestro');
     }

    public function bodega(){
        return $this->hasOne('App\Bodega', 'id');
    }

    public function estado(){
        return $this->belongsTo('App\estados', 'estado');
    }
}
