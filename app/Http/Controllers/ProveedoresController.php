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
use App\Proveedor;
use App\Events\ActualizacionBitacora;
use Validator;

class ProveedoresController extends Controller
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
         return view ("admin.proveedores.index");
     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
        return view("admin.proveedores.create");
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
         $proveedor = Proveedor::create($data);
         $proveedor->user_id = Auth::user()->id;
         $proveedor->save();

         event(new ActualizacionBitacora($proveedor->id, Auth::user()->id,'Creación','', $proveedor,'proveedores'));
         return redirect()->route('proveedores.index')->withFlash('El proveedor ha sido creado exitosamente!');
         //return Response::json($proveedor);
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
     public function nitDisponible()
     {
         $dato = Input::get("nit");
         $query = Proveedor::where("nit",$dato)->get();
         $contador = count($query);
         if ($contador == 0)
         {
             return 'false';
         }
         else
         {
             return 'true';
         }
     }
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit(Proveedor $proveedor)
     {
        return view('admin.proveedores.edit', compact('proveedor'));
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Proveedor $proveedor, Request $request)
     {

        $this->validate($request,['nit' => 'required|unique:proveedores,nit,'.$proveedor->id
        ]);
        $nuevos_datos = array(
            'nombre_comercial' => $request->nombre_comercial,
            'nombre_legal' => $request->nombre_legal,
            'nit' => $request->nit,
            'telefono' => $request->telefono,
            'contacto1' => $request->contacto1,
            'contacto2' => $request->contacto2,
            'direccion' => $request->direccion,
            'user_id' => Auth::user()->id,
            'email' => $request->email,
            'dias_credito' => $request->dias_credito
            );
        $json = json_encode($nuevos_datos);

        event(new ActualizacionBitacora($proveedor->id, Auth::user()->id,'Edición',$proveedor, $json,'proveedores'));

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index', $proveedor)->with('flash','El proveedor ha sido actualizado!');
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy(Proveedor $proveedor, Request $request)
     {
        $password_usuario = Auth::user()->password;

        $data = $request->all();

        $errors = Validator::make($data,[
            'password_actual' => ['required'],
        ]);

         if($errors->fails())
         {
            return  Response::json($errors->errors(), 422);
         }

         if(password_verify($data['password_actual'],$password_usuario))
         {
            $proveedor->estado = 0;
            $proveedor->save();
            event(new ActualizacionBitacora($proveedor->id, Auth::user()->id,'Inactivación','','','proveedores'));

            return Response::json(['success' => 'Éxito']);
         }
         else{
            return  Response::json(['password_actual' => 'La contraseña no coincide'], 422);
         }
     }

     public function activar(Proveedor $proveedor, Request $request)
     {
        $proveedor->estado = 1;
        $proveedor->save();
        event(new ActualizacionBitacora($proveedor->id, Auth::user()->id,'Activación','','','proveedores'));

        return Response::json(['success' => 'Éxito']);
     }

     public function getJson(Request $params)
     {
         $api_Result['data'] = Proveedor::all();
         return Response::json( $api_Result );
     }
}
