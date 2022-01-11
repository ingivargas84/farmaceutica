<?php

namespace App\Http\Controllers;

use App\Bodega;
use App\Events\ActualizacionBitacora;
use App\TipoBodega;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class BodegasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.bodegas.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipos = TipoBodega::all();
        return Response::json($tipos);
    }


    public function create1()
    {
      /* $query = "SELECT users.id, name, username
         FROM users
         WHERE (name = 'administrador' or name = 'vendedor')
          and estado = 1 and estado_bodega = 1";*/

          $query = "SELECT users.name as name, users.id
          FROM users
          INNER JOIN model_has_roles ON users.id = model_id
          INNER JOIN roles ON roles.id = model_has_roles.role_id
          WHERE (roles.id = 2 or roles.id = 3)
          and estado = 1 and estado_bodega = 1";

      $usuario = DB::select($query);
        return Response::json($usuario);
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
        $bodega = Bodega::create($data);
        $bodega->save();

        $usu1 = User::findOrFail($request->user_id);
        $usu1->estado_bodega = 2;
        $usu1->save();

        event(new ActualizacionBitacora($bodega->id, Auth::user()->id, 'Creación', '', $bodega, 'bodegas'));

        return Response::json(['success' => 'Éxito']);
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
    public function edit(Bodega $bodega, Request $request)
    {
        $bd = Bodega::find($bodega->id);
        return Response::json($bd);
    }

    public function edit1(Bodega $bodega, Request $request)
    {
        $bd = Bodega::find($bodega->id);
        $us = User::findOrFail($bd->user_id);
        return Response::json($us);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bodega $bodega)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'descripcion' => 'required',
            'tipo' => 'required',
            'user_id_edit' => 'required',
        ]);

        $usu1 = User::findOrFail($request->id_usuario);
        $usu1->estado_bodega = 1;
        $usu1->save();

        $nuevos_datos = array(
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'user_id' => $request->user_id_edit,
        );

        $usu = User::findOrFail($request->user_id_edit);
        $usu->estado_bodega = 2;
        $usu->save();

        $b = Bodega::findOrFail($request->id);
        $b->user_id = $request->user_id_edit;
        $b->save();

        $json = json_encode($nuevos_datos);

        event(new ActualizacionBitacora($bodega->id, Auth::user()->id, 'Edición', $bodega, $json, 'bodegas'));

        $bodega->update($request->all());

        return Response::json(['success' => 'Éxito']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bodega $bodega, Request $request)
    {
        $bodega->estado = 2;
        $bodega->save();

        $usu1 = User::findOrFail($bodega->user_id);
        $usu1->estado_bodega = 1;
        $usu1->save();
        /*$usu1 = User::findOrFail($request->user_id);
        $usu1->estado_bodega = 1;
        $usu1->save();*/

        event(new ActualizacionBitacora($bodega->id, Auth::user()->id, 'Inactivación', '', '', 'bodegas'));

        return Response::json(['success'=> 'Éxito']);
    }

    public function activar(Bodega $bodega, Request $request){

        $usu1 = User::findOrFail($bodega->user_id);

        if ($usu1->estado_bodega === 2) {
          return redirect()->route('bodegas.index')->withFlash('El usuario, ya tiene asignada una bodega activa.');
        //  $a = null;
          //return Response::json();
        }else {
          $bodega->estado = 1;
          $bodega->save();

         $usu1 = User::findOrFail($bodega->user_id);
          $usu1->estado_bodega = 2;
          $usu1->save();

          $usu = User::select(
            'users.*'
            )->get();


          event(new ActualizacionBitacora($bodega->id, Auth::user()->id, 'Activación', '', '', 'bodegas'));

          return redirect()->route('bodegas.index')->withFlash('Bodega Activada con Éxito!!');
          //return Response::json(['success'=> 'Éxito']);
        }


    }

    public function getJson(Request $params){
        $api_Result['data'] = Bodega::select(
            'bodegas.nombre', 'bodegas.descripcion',
            'tipos_bodega.tipo', 'bodegas.estado',
            'bodegas.id', 'users.name as encargado'
        )->join(
            'tipos_bodega', 'bodegas.tipo', '=', 'tipos_bodega.id'
        )->join(
          'users', 'bodegas.user_id', '=', 'users.id'
          )->get();

        return Response::json($api_Result);
    }
}
