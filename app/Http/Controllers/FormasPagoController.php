<?php

namespace App\Http\Controllers;

use App\Events\ActualizacionBitacora;
use App\FormaPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class FormasPagoController extends Controller
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
        return view('admin.formas_pago.index');
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
        $formaPago = FormaPago::create($data);
        $formaPago->save();

        event(new ActualizacionBitacora($formaPago->id, Auth::user()->id, 'Creación', '', $formaPago, 'formas_pago'));

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
    public function edit(FormaPago $formaPago, Request $request)
    {
        $fp = FormaPago::find($formaPago->id);
        return Response::json($fp);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormaPago $formaPago)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);

        $nuevos_datos = array(
            'nombre' => $request->nombre
        );

        $json = json_encode($nuevos_datos);

        event(new ActualizacionBitacora($formaPago->id, Auth::user()->id, 'Edición', $formaPago, $json, 'formas_pago'));

        $formaPago->update($request->all());

        return Response::json(['success'=>'Éxito']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormaPago $formaPago, Request $request)
    {
        $formaPago->estado = 2;
        $formaPago->save();

        event(new ActualizacionBitacora($formaPago->id, Auth::user()->id, 'Inactivación', '', '', 'formas_pago'));

        return Response::json(['success'=>'Éxito']);
    }

    // public function activar(FormaPago $formaPago, Request $request)
    // {
    //     $formaPago->estado = 1;
    //     $formaPago->save();

    //     event(new ActualizacionBitacora($formaPago->id, Auth::user()->id, 'Activación', '', '', 'formas_pago'));

    //     return Response::json(['success'=>'Éxito']);
    // }

    public function getJson(Request $params){
        $api_result['data'] = FormaPago::select()->where('estado', '=', '1')->get();

        return Response::json($api_result);
    }

    public function nombreDisponible(){
        $dato = Input::get("nombre");
        $query = FormaPago::where([
            ['nombre', '=', $dato],
            ['estado', '=', '1'],
            ])->get();
        $contador = count($query);
        if ($contador == 0) {
            return 'false';
        } else {
            return 'true';
        }
    }
}
