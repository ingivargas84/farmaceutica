<?php

namespace App\Http\Controllers;

use App\Bodega;
use App\Events\ActualizacionBitacora;
use App\MovimientoProducto;
use App\Producto;
use App\TraspasoBodega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TraspasosBodegaController extends Controller
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
        return view('admin/traspasos_bodega/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bodegas = Bodega::select()->where('estado', '=', '1')->get();
        $productos = Producto::where('estado', 1)->get();
        return view('admin/traspasos_bodega/create', compact('bodegas', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bodega_origen  = $request->bodega_origen;
        $bodega_destino = $request->bodega_destino;
        $producto_id    = $request->producto_id;
        $cantidad       = $request->cantidad;

        $tp = TraspasoBodega::create([
            'cantidad'       => $cantidad,
            'bodega_origen'  => $bodega_origen,
            'bodega_destino' => $bodega_destino,
            'producto_id'    => $producto_id,
            'user_id'        => Auth::user()->id,
        ]);

        event(new ActualizacionBitacora($tp->id, Auth::user()->id, 'Creación', '', $tp, 'traspasos_bodega'));

        $movimientos = MovimientoProducto::select()->where([
                ['producto_id', $producto_id],
                ['bodega_id',   $bodega_origen]
            ])->orderBy(
                'caducidad'
            )->get();

        $comp = 0;//this variable is to be compared to the transfer ammount.

        if ( $cantidad <= $movimientos->first()->existencias ) {
            $movimientos->first()->decrement('existencias', $cantidad);
            $mp = MovimientoProducto::create([
                'existencias'   => $cantidad,
                'caducidad'     => $movimientos->first()->caducidad,
                'bodega_id'     => $bodega_destino,
                'precio_compra' => $movimientos->first()->precio_compra,
                'producto_id'   => $producto_id
                ]);
                event(new ActualizacionBitacora($mp->id, Auth::user()->id, 'Creación', '', $mp, 'movimientos_producto'));
        } else {
            foreach ($movimientos as $mov) {
                while ($mov->existencias != 0 && $comp < $cantidad) {
                    $mov->decrement('existencias');
                    $comp ++;
               } ;
            }
            $mp = MovimientoProducto::create([
                'existencias'   => $cantidad,
                'caducidad'     => $movimientos->first()->caducidad,
                'bodega_id'     => $bodega_destino,
                'precio_compra' => $movimientos->first()->precio_compra,
                'producto_id'   => $producto_id
            ]);
            event(new ActualizacionBitacora($mp->id, Auth::user()->id, 'Creación', '', $mp, 'movimientos_producto'));
        }
        return redirect()->route('traspasos_bodega.index');
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

    public function getJson(){
        $apiResult['data'] = TraspasoBodega::select(
            'traspasos_bodega.id',
            'traspasos_bodega.created_at',
            'traspasos_bodega.cantidad',
            'b1.nombre as bodega_origen',
            'b2.nombre as bodega_destino',
            'productos.nombre_comercial as producto',
            'users.name as user'
        )->join(
            'bodegas as b1',
            'b1.id',
            '=',
            'traspasos_bodega.bodega_origen'
        )->join(
            'bodegas as b2',
            'b2.id',
            '=',
            'traspasos_bodega.bodega_destino'
        )->join(
            'productos',
            'productos.id',
            '=',
            'traspasos_bodega.producto_id'
        )->join(
            'users',
            'users.id',
            '=',
            'traspasos_bodega.user_id'
        )->get();

        return Response::json($apiResult);
    }

    public function getProduct(Request $params, $id, Bodega $bodega){
        $apiResult = Producto::select(
            'productos.id',
            'productos.nombre_comercial',
            DB::raw('SUM(movimientos_producto.existencias) as existencias')
        )->join(
            'movimientos_producto',
            'movimientos_producto.producto_id',
            '=',
            'productos.id'
        )->where(
            'movimientos_producto.bodega_id',
            $bodega->id
        )->where(
            'productos.codigo',
            $id
        )->groupBy(
            'productos.id',
            'productos.nombre_comercial'
        )->get();

        return Response::json($apiResult);
    }

    public function getProductName(Request $params, $id, Bodega $bodega){
        $apiResult = Producto::select(
            'productos.id',
            'productos.nombre_comercial',
            DB::raw('SUM(movimientos_producto.existencias) as existencias')
        )->join(
            'movimientos_producto',
            'movimientos_producto.producto_id',
            '=',
            'productos.id'
        )->where(
            'movimientos_producto.bodega_id',
            $bodega->id
        )->where(
            'productos.nombre_comercial',
            $id
        )->where(
          'productos.estado',
          '=',
          '1'
          )->groupBy(
            'productos.id',
            'productos.nombre_comercial'
        )->get();

        return Response::json($apiResult);
    }
}
