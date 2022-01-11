<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\User;

class CajeroMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = auth()->user()->id;

        if (auth()->check() && !auth()->user()->hasRole('Cobrador')){
            return $next($request);
        }
        else{

            if(auth()->check() && auth()->user()->hasRole('Cobrador') && $contador > 0){
                return $next($request);             
            }
            else{
                auth()->logout();
                return redirect()
                ->route('login')
                ->with('MensajeEstado','El usuario ingresado no tiene caja abierta');
            }
            
        }
        
    }
}
