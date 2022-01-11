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
use App\territorios;
use App\Events\ActualizacionBitacora;
use Validator;

class TerritoriosController extends Controller
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

        return view ("admin.territorios.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $query = "SELECT users.name as name, users.id
      FROM users
      INNER JOIN model_has_roles ON users.id = model_id
      INNER JOIN roles ON roles.id = model_has_roles.role_id
      WHERE  roles.id = 3
      and estado = 1 ";

      $usuario = DB::select($query);
        return view("admin.territorios.create", compact('usuario'));
    }

    public function create1(){

      $query = "SELECT users.name as name, users.id as id
      FROM users
      INNER JOIN model_has_roles ON users.id = model_id
      INNER JOIN roles ON roles.id = model_has_roles.role_id
      WHERE  roles.id = 3
      and estado = 1 ";

      $usuario1 = DB::select($query);
      return Response::json($usuario1);

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
        $territorio = territorios::create($data);
        $territorio->save();

        event(new ActualizacionBitacora($territorio->id, Auth::user()->id,'Creación','', $territorio,'territorios'));
        return redirect()->route('territorios.index')->withFlash('El territorio ha sido creado exitosamente!');
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
    public function edit($id)
    {
      $query = "SELECT territorios.territorio as territorio,
         territorios.descripcion as descripcion,
         territorios.created_at as fecha,
         users.name as usuario,
         territorios.id as id,
         territorios.estado as estado,
         users.id as usuario_id
         FROM territorios
         LEFT JOIN users ON users.id = territorios.user
         where territorios.id = $id";
       $territorio = DB::select($query);
        return Response::json($territorio);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, territorios $territorio)
    {
        $this->validate($request, [
            'territorio' => 'required',
            'descripcion' => 'required',
            'user' => 'required',
        ]);

        $nuevos_datos = array(
            'territorio' => $request->nombre,
            'descripcion' => $request->descripcion,
            'user' => $request->user,
        );

        $json = json_encode($nuevos_datos);

        event(new ActualizacionBitacora($territorio->id, Auth::user()->id, 'Edición', $territorio, $json, 'territorios'));

        $territorio->update($request->all());

        return Response::json(['success' => 'Éxito']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(territorios $territorio, Request $request)
    {
        $territorio->estado = 2;
        $territorio->save();

        event(new ActualizacionBitacora($territorio->id, Auth::user()->id, 'Inactivación', '', '', 'territorios'));

        return Response::json(['success'=> 'Éxito']);
    }

    public function activar(territorios $territorio, Request $request)
     {
        $territorio->estado = 1;
        $territorio->save();

        event(new ActualizacionBitacora($territorio->id, Auth::user()->id,'Activación','','','territorios'));

        return Response::json(['success' => 'Éxito']);
     }

     public function getJson(Request $params)
     {
        $query = "SELECT territorios.territorio as territorio,
           territorios.descripcion as descripcion,
           territorios.created_at as fecha,
           users.name as usuario,
           territorios.id as id,
           territorios.estado as estado
           FROM territorios
           LEFT JOIN users ON users.id = territorios.user";
         $api_Result['data'] = DB::select($query);
         return Response::json( $api_Result );
     }

     public function territorioDisponible(territorios $territorio){
        $dato = Input::get("territorio");
        $query = territorios::where("territorio", $dato)->get();
        $contador = count($query);
        if ($contador == 0) {
            return 'false';
        } else {
            return 'true';
        }

     }

     public function territorioDisponibleEditar(territorios $territorio){
        $dato = Input::get("territorio");
          $id = Input::get('id');
        $query = territorios::where("territorio", $dato)->where('id', '!=', $id)->get();
        $contador = count($query);
        if ($contador == 0) {
            return 'false';
        } else {
            return 'true';
        }

     }



}
