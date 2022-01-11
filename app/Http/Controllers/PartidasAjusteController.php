<?php

namespace App\Http\Controllers;

use App\Bodega;
use App\Events\ActualizacionBitacora;
use App\MovimientoProducto;
use App\PartidaAjusteDetalle;
use App\PartidaAjusteMaestro;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PartidasAjusteController extends Controller
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
        return view('admin.partidas_ajuste.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $bodega = Bodega::find($id);
        $productos = Producto::select()->where('estado', '=', 1)->get();
        return view('admin.partidas_ajuste.create', compact('bodega', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //put all the request data in an array
        $data = json_decode($request->getContent(), true);
        // dd($data);

        $user_id = Auth::user()->id;

        //set the variables for creating the master
        $id_bodega     = $data[1]["value"];
        $total_ingreso = $data[4]["value"];
        $total_salida  = $data[5]["value"];
        $saldo         = $data[6]["value"];

        //create the PartidaAjusteMaestro
        $pam = PartidaAjusteMaestro::create([
            'total_ingreso' => $total_ingreso,
            'total_salida'  => $total_salida,
            'saldo'         => $saldo,
            'id_bodega'     => $id_bodega
        ]);
        event(new ActualizacionBitacora($pam->id, $user_id, 'Creación', '', $pam, 'partidas_ajuste'));

        //create the PartidaAjusteDetalle
        for ($i = 7; $i < sizeof($data); $i++) {
            $pad = PartidaAjusteDetalle::create([
                'cantidad'    => $data[$i]["cantidad"],
                'precio'      => $data[$i]["precio"],
                'ingreso'     => $data[$i]["ingreso"],
                'salida'      => $data[$i]["salida"],
                'tipo_ajuste' => $data[$i]["tipo_ajuste"],
                'id_producto' => $data[$i]["id_producto"],
                'id_partida_ajuste_maestro' => $pam->id,
            ]);
            event(new ActualizacionBitacora($pad->id, $user_id, 'Creación', '', $pad, 'partidas_ajuste_detalle'));

            $count = 0;

            /* gets all the product movement rows for the adjusted product
            and orders  them by expiration date */
            $movimientos = MovimientoProducto::select()->where([
                ['producto_id', $data[$i]["id_producto"]],
                ['bodega_id', $id_bodega]
            ])->orderBy(
                'caducidad'
            )->get();

            /* decrement or increment the product stock according
            to the type of adjustment detail, starting from the
            closest to expire */
            if ($data[$i]["tipo_ajuste"] == 1) {
                foreach ($movimientos as $mov) {
                    while ($count < $data[$i]["cantidad"]) {
                        $mov->increment('existencias');
                        $count++;
                    }
                }
            } else {
                foreach ($movimientos as $mov) {
                    while ($mov->existencias != 0 && $count < $data[$i]["cantidad"]) {
                        $mov->decrement('existencias');
                        $count++;
                    }
                }
            }//else
        }//for
        return Response::json(['success' => 'éxito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $partidaAjuste = PartidaAjusteMaestro::find($id);

        return view('admin.partidas_ajuste.show', compact('partidaAjuste'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getJson()
    {
        $data['data'] = PartidaAjusteMaestro::select(
            'id',
            'codigo_partida',
            'fecha_ingreso',
            'total_ingreso',
            'total_salida',
            'saldo'
        )->where(
            'estado',
            '=',
            1
        )->get();

        return Response::json($data);
    }

    public function getDetalles($id)
    {
        $data['data'] = PartidaAjusteDetalle::select(
            'productos.codigo as cod_producto',
            'productos.nombre_comercial as nombre_producto',
            'partidas_ajuste_detalle.cantidad',
            'partidas_ajuste_detalle.precio',
            'partidas_ajuste_detalle.ingreso',
            'partidas_ajuste_detalle.salida'
        )->join(
            'productos',
            'partidas_ajuste_detalle.id_producto',
            'productos.id'
        )->where(
            'partidas_ajuste_detalle.id_partida_ajuste_maestro',
            $id
        )->get();

        return Response::json($data);
    }
}
