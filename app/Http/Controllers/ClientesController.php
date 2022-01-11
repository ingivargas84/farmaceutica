<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Events\ActualizacionBitacora;
use App\territorios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Client;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('admin.clientes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $territorios = territorios::all();
        return view('admin.clientes.create', compact('territorios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->nacimiento_compras == !null){
            $nac_ec = date_format(date_create($request->nacimiento_compras), "Y/m/d");
        } else {
            $nac_ec = null;
        }
        if($request->nacimiento_paga == !null){
            $nac_ep = date_format(date_create($request->nacimiento_paga), "Y/m/d");
        } else {
            $nac_ep = null;
        }

        $cliente = Cliente::create([
            'nombre_cliente'     => $request->nombre_cliente,
            'dias_credito'       => $request->dias_credito,
            'nit'                => $request->nit,
            'encargado_compras'  => $request->encargado_compras,
            'nacimiento_compras' => $nac_ec,
            'telefono_compras'   => $request->telefono_compras,
            'encargado_paga'     => $request->encargado_paga,
            'nacimiento_paga'    => $nac_ep,
            'telefono_paga'      => $request->telefono_paga,
            'direccion'          => $request->direccion,
            'email'              => $request->email,
            'territorio'         => $request->territorio,
        ]);
        $cliente->save();

        event(new ActualizacionBitacora($cliente->id, Auth::user()->id, 'Creación', '', $cliente, 'clientes'));

        return redirect()->route('clientes.index')->withFlash('El cliente se ha registrado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        $territorios = territorios::all();
        return view('admin.clientes.edit', compact('cliente', 'territorios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $this->validate($request,[
            'direccion' => 'required',
            'dias_credito' => 'required',
            'nombre_cliente' => 'required'
        ]);
        $nuevos_datos = array(
            'nit' => $request->nit,
            'encargado_compras' => $request->encargado_compras,
            'nacimiento_compras' => $request->nacimiento_compras,
            'telefono_compras' => $request->telefono_compras,
            'encargado_paga' => $request->encargado_paga,
            'nacimiento_paga' => $request->nacimiento_paga,
            'telefono_paga' => $request->telefono_paga,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'territorio' => $request->territorio,
            'dias_credito' => $request->dias_credito,
            'nombre_cliente' => $request->nombre_cliente

        );

        $json = json_encode($nuevos_datos);

        event(new ActualizacionBitacora($cliente->id, Auth::user()->id, 'Edición', $cliente, $json, 'clientes'));

        if($request->nacimiento_compras == !null){
            $nac_ec = date_format(date_create($request->nacimiento_compras), "Y/m/d");
        } else {
            $nac_ec = null;
        }
        if($request->nacimiento_paga == !null){
            $nac_ep = date_format(date_create($request->nacimiento_paga), "Y/m/d");
        } else {
            $nac_ep = null;
        }


        $cliente->update([
            'nombre_cliente'     => $request->nombre_cliente,
            'dias_credito'       => $request->dias_credito,
            'nit'                => $request->nit,
            'encargado_compras'  => $request->encargado_compras,
            'nacimiento_compras' => $nac_ec,
            'telefono_compras'   => $request->telefono_compras,
            'encargado_paga'     => $request->encargado_paga,
            'nacimiento_paga'    => $nac_ep,
            'telefono_paga'      => $request->telefono_paga,
            'direccion'          => $request->direccion,
            'email'              => $request->email,
            'territorio'         => $request->territorio,
        ]);

        return redirect()->route('clientes.index', $cliente)->withFlash('El cliente se ha actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente, Request $request)
    {
        $cliente->estado = 2;
        $cliente->save();

        event(new ActualizacionBitacora($cliente->id, Auth::user()->id, 'Inactivación', '', '', 'clientes'));

        return Response::json(['success'=>'Éxito']);
    }

    public function activar(Cliente $cliente, Request $request)
    {
        $cliente->estado = 1;
        $cliente->save();

        event(new ActualizacionBitacora($cliente->id, Auth::user()->id, 'Activación', '', '', 'clientes'));

        return Response::json(['success'=> 'Éxito']);
    }

    public function getJson(Request $params)
    {
        $api_Result['data'] = Cliente::select(
            'clientes.nombre_cliente', 'clientes.nit',
            'clientes.telefono_compras','territorios.territorio',
            'clientes.estado', 'clientes.id'
            )->join(
                'territorios', 'clientes.territorio', '=', 'territorios.id'
                )->get();
        return Response::json($api_Result);
    }
    
    public function nitDisponible()
    {
        $dato = Input::get("nit");
        $query = Cliente::where("nit", $dato)->get();
        $contador = count($query);
        if ($contador == 0) {
            return 'false';
        } else {
            return 'true';
        }
    }

}
