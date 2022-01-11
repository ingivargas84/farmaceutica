<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Negocio;
use Illuminate\Support\Facades\Storage;
use Validator;
use Image;

class NegocioController extends Controller
{
    public function edit(Negocio $negocio)
    {
        $this->authorize('update', $negocio);

        return view('admin.negocio.edit', compact('negocio'));
    }

    public function update(Negocio $negocio, Request $request)
    {
        $this->authorize('update', $negocio);

        if($request->hasfile('logotipo'))
        {
            $this->validate($request, [
                'logotipo' => 'image|mimes:png'
            ]);

            $image = Image::make($request->file('logotipo')->getRealPath());
            $image->encode('data-url');

            $negocio->logotipo = $image;

            $negocio->save();            
        }

        $negocio->update([
            'nit' => $request['nit'],
            'nombre_contable' => $request['nombre_contable'],
            'nombre_comercial' => $request['nombre_comercial'],
            'direccion' => $request['direccion'],
            'telefonos' => $request['telefonos'],
            'email' => $request['email'],
            'fecha_inicio' => $request['fecha_inicio'],
            'no_patente' => $request['no_patente']
        ]);
     
       return redirect()->route('negocio.edit', $negocio)->with('flash','La información del negocia ha sido guardada');
    }
}
