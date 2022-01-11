<?php

namespace App\Http\Middleware;

use Closure;

class UserInactivoMiddleware
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
        if (auth()->check() && !auth()->user()->estado == false )
        return $next($request);

        else {
            auth()->logout();
            return redirect()
            ->route('login')
            ->with('MensajeEstado','El usuario ingresado esta inactivo');
        }
    }
}
