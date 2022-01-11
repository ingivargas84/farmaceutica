<?php

namespace App\Policies;

use App\User;
use App\Negocio;
use Illuminate\Auth\Access\HandlesAuthorization;

class NegocioPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if( $user->hasRole('Administrador|Super-Administrador') )
        {
            return true;
        }
    }
    /**
     * Determine whether the user can view the negocio.
     *
     * @param  \App\User  $user
     * @param  \App\Negocio  $negocio
     * @return mixed
     */
    public function view(User $user, Negocio $negocio)
    {
        //
    }

    /**
     * Determine whether the user can create negocios.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the negocio.
     *
     * @param  \App\User  $user
     * @param  \App\Negocio  $negocio
     * @return mixed
     */
    public function update(User $user, Negocio $negocio)
    {
    }

    /**
     * Determine whether the user can delete the negocio.
     *
     * @param  \App\User  $user
     * @param  \App\Negocio  $negocio
     * @return mixed
     */
    public function delete(User $user, Negocio $negocio)
    {
        //
    }

    /**
     * Determine whether the user can restore the negocio.
     *
     * @param  \App\User  $user
     * @param  \App\Negocio  $negocio
     * @return mixed
     */
    public function restore(User $user, Negocio $negocio)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the negocio.
     *
     * @param  \App\User  $user
     * @param  \App\Negocio  $negocio
     * @return mixed
     */
    public function forceDelete(User $user, Negocio $negocio)
    {
        //
    }
}
