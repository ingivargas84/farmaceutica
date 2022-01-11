<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\User;
use App\Visita;
use App\Cliente;
use App\Events\ActualizacionBitacora;
use Validator;

class VisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
       $this->middleware('auth');
    }


    public function index()
    {
        $usuario = User::where("estado", 1)
                        ->where("id",">=",5)->get() ;
        return view ("admin.visitas.index", compact('usuario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::all();
        return view('admin.visitas.create', compact('clientes'));
    }

    public function create2()
    {
        return view('admin.visitas.create2');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $visita = Visita::create($data);
        $visita->user_id = Auth::user()->id;
        $visita->estado = 1;
        $visita->nombre_cliente = "";
        $visita->telefono_cliente = "";
        $visita->direccion_cliente = "";
        //$visita->fecha = date('Y-m-d');
        $visita->save();

        event(new ActualizacionBitacora($visita->id, Auth::user()->id,'Creación','', $visita,' visitas'));
        return redirect()->route('visitas.index')->withFlash('La visita ha sido creada exitosamente!');
    }
    

    public function store2(Request $request)
    {
        $data = $request->all();
        $visita = Visita::create($data);
        $visita->user_id = Auth::user()->id;
        $visita->estado = 1;
        //$visita->fecha = date('Y-m-d');
        $visita->save();

        event(new ActualizacionBitacora($visita->id, Auth::user()->id,'Creación','', $visita,' visitas'));
        return redirect()->route('visitas.index')->withFlash('La visita ha sido creada exitosamente!');
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
    public function edit(Visita $visita)
    {
        $clientes = Cliente::all();
        return view('admin.visitas.edit', compact('clientes','visita'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visita $visita)
    {
        $this->validate($request,[
            'cliente_id'=>'required',
            'observaciones'=>'required',
        ]);

        $nuevos_datos = array(
            'cliente_id' => $request->cliente_id,
            'observaciones' => $request->observaciones,
        );

        $json = json_encode($nuevos_datos);

        event(new ActualizacionBitacora($visita->id, Auth::user()->id, 'Edición', $visita, $json, 'visitas'));

        $visita->update($request->all());

        return redirect()->route('visitas.index', $visita)->withFlash('La visita se ha actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visita $visita, Request $request)
    {
        $visita->estado = 2;
        $visita->save();

        event(new ActualizacionBitacora($visita->id, Auth::user()->id, 'Eliminación', '', '', 'visitas'));

        return Response::json(['success'=> 'Éxito']);
    }


    public function getJson(Request $params)
     {

       $usuarios = User::select(
         'model_has_roles.role_id as id'
         )->join(
            'model_has_roles',
            'model_has_roles.model_id',
            '=',
            'users.id'
           )->where(
             'users.id',
             '=',
             Auth::user()->id
             )->get()->first();

        if ($usuarios->id ==2 or $usuarios->id ==1) {

          $api_result['data'] = Visita::select(
              'visitas.id',
              DB::raw('IF(visitas.cliente_id>0, clientes.nombre_cliente, visitas.nombre_cliente) as nombre_cliente'),
              DB::raw('IF(visitas.cliente_id>0, clientes.direccion, visitas.direccion_cliente) as direccion'),
              'visitas.observaciones',
              'visitas.created_at'
          )->join(
              'clientes',
              'visitas.cliente_id',
              '=',
              'clientes.id'
          )->where(
              'visitas.estado',
              '=',
              1
          )->get();

          return Response::json($api_result);

        }else {
        $api_result['data'] = Visita::select(
            'visitas.id',
            DB::raw('IF(visitas.cliente_id>0, clientes.nombre_cliente, visitas.nombre_cliente) as nombre_cliente'),
            DB::raw('IF(visitas.cliente_id>0, clientes.direccion, visitas.direccion_cliente) as direccion'),
            'visitas.observaciones',
            'visitas.created_at'
        )->join(
            'clientes',
            'visitas.cliente_id',
            '=',
            'clientes.id'
        )->where(
            'visitas.estado',
            '=',
            1
        )->where(
            'visitas.user_id',
            '=',
            Auth::user()->id
        )->get();

        return Response::json($api_result);
        }
     }
}
