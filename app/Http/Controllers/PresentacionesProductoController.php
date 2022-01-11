<?php

namespace App\Http\Controllers;

use App\Events\ActualizacionBitacora;
use App\PresentacionProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Response;

class PresentacionesProductoController extends Controller
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
        return view('admin.presentaciones_producto.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $presentacionProducto = PresentacionProducto::create($data);
        $presentacionProducto->save();

        event(new ActualizacionBitacora($presentacionProducto->id, Auth::user()->id, 'Creación', '', $presentacionProducto, 'presentaciones_producto'));

        return Response::json(['success'=>'Éxito']);
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
    public function edit(PresentacionProducto $presentacionProducto, Request $request)
    {
        $pp = PresentacionProducto::find($presentacionProducto->id);
        return Response::json($pp);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PresentacionProducto $presentacionProducto)
    {
        $this->validate($request, [
            'presentacion'=>'required'
        ]);

        $nuevos_datos = array(
            'presentacion' => $request->presentacion
        );

        $json = json_encode($nuevos_datos);

        event(new ActualizacionBitacora($presentacionProducto->id, Auth::user()->id, 'Edición', $presentacionProducto, $json, 'presentaciones_producto'));

        $presentacionProducto->update($request->all());

        return Response::json(['success'=>'Éxito']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PresentacionProducto $presentacionProducto, Request $request)
    {
        $presentacionProducto->estado = 2;
        $presentacionProducto->save();

        event(new ActualizacionBitacora($presentacionProducto->id, Auth::user()->id, 'Inactivación', '', '', 'presentaciones_producto'));

        return Response::json(['sucess'=> 'Éxito']);
    }

    // public function activar(PresentacionProducto $presentacionProducto, Request $request)
    // {
    //     $presentacionProducto->estado = 1;
    //     $presentacionProducto->save();

    //     event(new ActualizacionBitacora($presentacionProducto->id, Auth::user()->id, 'Activación', '', '', 'presentaciones_producto'));

    //     return Response::json(['success'=>'Éxito']);
    // }

    public function getJson(Request $request){
        $api_result['data'] = PresentacionProducto::select()->where('estado', '1')->get();

        return Response::json($api_result);
    }

    public function nombreDisponible()
    {
        $dato = Input::get("nombre");
        $query = PresentacionProducto::where([
            ['presentacion', '=', $dato],
            ['estado', '=', '1']
        ])->get();
        $contador = count($query);
        if ($contador == 0) {
            return 'false';
        } else {
            return 'true';
        }
    }
}
