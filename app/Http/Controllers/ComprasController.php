<?php

namespace App\Http\Controllers;

use App\Bodega;
use App\CuentaPagarDetalleCompra;
use App\CuentaPagarMaestro;
use App\EstadoCuentaProveedor;
use App\Events\ActualizacionBitacora;
use App\IngresoDetalle;
use App\IngresoMaestro;
use App\MovimientoProducto;
use App\Producto;
use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

use function PHPSTORM_META\map;

class ComprasController extends Controller
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
        return view('admin.compras.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $proveedores = Proveedor::select()->where(
            'proveedores.estado', '=', '1'
        )->get();

        $bodegas = Bodega::select()->where([
            ['bodegas.tipo', '=', '1'],
            ['bodegas.estado', '=', '1']
        ])->get();

        $productos = Producto::where('estado', 1)->get();

        return view('admin.compras.create', compact('proveedores', 'bodegas', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arr = json_decode($request->getContent(), true);
        // dd($arr);

        $user_id       = Auth::user()->id;
        $proveedor_id  = $arr[1]["value"];
        $bodega_id     = $arr[2]["value"];
        $serie_factura = $arr[3]["value"];
        $num_factura   = $arr[4]["value"];
        $fecha_factura = date_format(date_create($arr[5]["value"]), "Y/m/d");
        $fecha_compra  = date_format(date_create($arr[6]["value"]), "Y/m/d");
        $total_ingreso = $arr[15]["value"];

        $im = IngresoMaestro::create([
            'user_id'       => $user_id,
            'proveedor_id'  => $proveedor_id,
            'serie_factura' => $serie_factura,
            'bodega_id'     => $bodega_id,
            'fecha_compra'  => $fecha_compra,
            'num_factura'   => $num_factura,
            'fecha_factura' => $fecha_factura,
            'total_ingreso' => $total_ingreso,
            ]);

            $ecp = EstadoCuentaProveedor::create([
                'documento_id' => $im->id,
                'proveedor_id' => $proveedor_id,
                ]);

        for ($i=16; $i < sizeof($arr) ; $i++) {
            $mp = MovimientoProducto::create([
                'bodega_id'     => $bodega_id,
                'existencias'   => $arr[$i]["cantidad"],
                'caducidad'     => date_format(date_create($arr[$i]["caducidad"]), "Y/m/d"),
                'precio_compra' => $arr[$i]["precio_compra"],
                'producto_id'   => $arr[$i]["producto_id"]
            ]);

            $id = IngresoDetalle::create([
                'precio_compra'          => $arr[$i]["precio_compra"],
                'cantidad'               => $arr[$i]["cantidad"],
                'producto_id'            => $arr[$i]["producto_id"],
                'subtotal'               => $arr[$i]["subtotal"],
                'ingreso_maestro_id'     => $im->id,
                "movimiento_producto_id" => $mp->id,
            ]);
        }

        //checks if there is already an account master and writes to log
        if (CuentaPagarMaestro::find($proveedor_id)) {
            $cpm = CuentaPagarMaestro::find($proveedor_id);
            event(new ActualizacionBitacora($cpm->id, Auth::user()->id, 'Creación', '', $cpm, 'cuentas_pagar_maestro'));
        }

        //Creates or updates the CuentaPagarMaestro register
        $cuenta = CuentaPagarMaestro::firstOrCreate(['id_proveedor'=>$proveedor_id]);
        $cuenta->increment('saldo', $total_ingreso);
        $cuenta->save();

        //creates the purchase detail (CuentaPagarDetalleCompra)
        $cpdc = CuentaPagarDetalleCompra::create(
            [
                'ingreso_id' => $im->id,
                'cuentas_pagar_maestro_id' => $cuenta->id
            ]
        );
        //Writes the new purchase detail to log
        event(new ActualizacionBitacora($cpdc->id, Auth::user()->id, 'Creación', '', $cpdc, 'cuentas_pagar_detalle_compra'));

        //writes the new purchase to log
        event(new ActualizacionBitacora($im->id, Auth::user()->id, 'Creación', '', $im, 'ingresos_maestro'));

        return Response::json(['success' => 'Éxito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IngresoMaestro $ingresoMaestro)
    {
        $compra = IngresoMaestro::select(
            'ingresos_maestro.id',
            'ingresos_maestro.fecha_factura',
            'ingresos_maestro.fecha_compra',
            'ingresos_maestro.serie_factura',
            'ingresos_maestro.num_factura',
            'ingresos_maestro.total_ingreso',
            'users.name as user',
            'bodegas.nombre as bodega',
            'proveedores.nombre_comercial as proveedor'
        )->where(
            'ingresos_maestro.id',
            '=',
            $ingresoMaestro->id
        )->join(
            'users',
            'users.id',
            '=',
            'ingresos_maestro.user_id'
        )->join(
            'proveedores',
            'proveedores.id',
            '=',
            'ingresos_maestro.proveedor_id'
        )->join(
            'bodegas',
            'bodegas.id',
            '=',
            'ingresos_maestro.bodega_id'
        )->get();
        return view('admin.compras.show', compact('compra'));
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
    public function destroy(IngresoMaestro $ingresoMaestro, Request $request)
    {
        //adjusts the balance in the account
        $ingresoMaestro->cuentaPagarDetalleCompra->cuentaPagarMaestro->decrement(
                'saldo',
                $ingresoMaestro->cuentaPagarDetalleCompra->monto()
        );
        //soft-deletes the purchase detail from the account
        $ingresoMaestro->cuentaPagarDetalleCompra->update(['estado'=> 2]);

        $ingresoMaestro->estado_ingreso = 2;
        $ingresoMaestro->save();
        $ecp = $ingresoMaestro->proveedor()->get()[0]->estadoCuentaProveedor()->get()[0];
        $ecp->estado = 2;
        $ecp->save();
        for ($i = 0; $i < $ingresoMaestro->ingresosDetalle()->where('estado', '=', 1)->count() ; $i++) {
            $ingresoMaestro->ingresosDetalle()->get()[$i]->estado = 2;
            $ingresoMaestro->ingresosDetalle()->get()[$i]->save();
            $ingresoMaestro->ingresosDetalle()->get()[$i]->movimientoProducto()->get()[0]->decrement(
                'existencias', $ingresoMaestro->ingresosDetalle()->get()[$i]->cantidad
            );
        }

        event(new ActualizacionBitacora($ingresoMaestro->id, Auth::user()->id, 'Eliminación', '', '', 'ingresos_maestro'));

        return Response::json(['success'=>'Éxito']);
    }

    public function destroyDetalle(IngresoDetalle $ingresoDetalle, Request $request)
    {
        //get the detail's master for soft deletion and balance adjustment
        $im = $ingresoDetalle->ingresoMaestro()->get()[0];

        if ($ingresoDetalle->cantidad = $ingresoDetalle->movimientoProducto()->get()[0]->existencias) {

            //soft deletes the detail
            $ingresoDetalle->estado = 2;
            $ingresoDetalle->save();
            $ecp = $ingresoDetalle->ingresoMaestro()->get()[0]->proveedor()->get()[0]->estadoCuentaProveedor()->get()[0];
            $ecp->estado = 2;
            $ecp->save();

            //adjusts the balance in the account
            $ingresoDetalle->ingresoMaestro->cuentaPagarDetalleCompra->cuentaPagarMaestro->decrement('saldo', $ingresoDetalle->subtotal);
            //subtracts the subtotal from the purchase total
            $ingresoDetalle->ingresoMaestro()->get()[0]->decrement(
                'total_ingreso',
                $ingresoDetalle->subtotal
            );

            //removes the stock of the item
            $ingresoDetalle->movimientoProducto()->get()[0]->decrement(
                'existencias',
                $ingresoDetalle->cantidad
            );

            //reports the deletion to the log
            event(new ActualizacionBitacora(
                $ingresoDetalle->id,
                Auth::user()->id,
                'Eliminación', '', '', 'ingresos_detalle'));

            //cheks if the soft-deleted detail is the last one from the master
            if (sizeOf($ingresoDetalle->ingresoMaestro()->get()[0]->ingresosDetalle()->where('estado','=', 1)->get()) < 1) {

                //soft-deletes the master
                $im->estado_ingreso = 2;
                $im->save();

                //soft-deletes the purchase detail from the account
                $im->cuentaPagarDetalleCompra->update(['estado' => 2]);

                //reports the master deletion to the log
                event(new ActualizacionBitacora(
                    $ingresoDetalle->ingresoMaestro()->get()[0]->id,
                    Auth::user()->id,
                    'Eliminación', '', '', 'ingresos_maestro'
                ));

                //returns a response that will redirect back
                return Response::json([
                    'success'=> 'Éxito',
                    'back'   => 'true'
                    ]);
            }else{
                //returns a response that will notify a successful deletion
                return Response::json(['success' => 'Éxito']);
            }
        }else {
            //returns an deletion error
            return Response::json(['error'=>'No se puede este detalle.'], 500);
        }


    }

    public function getJson(Request $params){
        $api_result['data'] = IngresoMaestro::select(
            'ingresos_maestro.fecha_factura as fecha',
            'proveedores.nombre_comercial as proveedor',
            'ingresos_maestro.num_factura as noFactura',
            'ingresos_maestro.total_ingreso as total',
            'ingresos_maestro.id',
            DB::raw('SUM(movimientos_producto.existencias) as existencias'),
            DB::raw('SUM(ingresos_detalle.cantidad) as cantidad')
        )->join(
            'proveedores',
            'ingresos_maestro.proveedor_id',
            '=',
            'proveedores.id'
        )->join(
            'ingresos_detalle',
            'ingresos_maestro.id',
            '=',
            'ingresos_detalle.ingreso_maestro_id'
        )->join(
            'movimientos_producto',
            'ingresos_detalle.movimiento_producto_id',
            '=',
            'movimientos_producto.id'
        )->where([
            [
                'ingresos_maestro.estado_ingreso',
                '=',
                '1'
            ]
        ])->groupBy(
            'ingresos_maestro.id',
            'ingresos_maestro.fecha_factura',
            'proveedores.nombre_comercial',
            'ingresos_maestro.num_factura',
            'ingresos_maestro.total_ingreso'
        )->get();

        return Response::json($api_result);
    }

    //gets the details of an IngresoMaestro for the show view.
    public function getDetalles(Request $params, IngresoMaestro $ingresoMaestro){
        $api_result['data'] = $ingresoMaestro->ingresosDetalle()->join(
            'productos',
            'ingresos_detalle.producto_id',
            '=',
            'productos.id'
        )->join(
            'movimientos_producto',
            'ingresos_detalle.movimiento_producto_id',
            '=',
            'movimientos_producto.id'
        )->select(
            'ingresos_detalle.id',
            'ingresos_detalle.fecha_ingreso',
            'ingresos_detalle.precio_compra',
            'ingresos_detalle.cantidad',
            'productos.nombre_comercial as producto',
            'movimientos_producto.existencias',
            'movimientos_producto.caducidad'
        )->where(
            'ingresos_detalle.estado',
            '=',
            '1'
        )->get();

        return Response::json($api_result);
    }

    //get the selected product data for the create view
    public function getProductoData($id){
        $api_result = Producto::select(
            'productos.*',
            'presentaciones_producto.presentacion'
        )->join(
            'presentaciones_producto',
            'presentaciones_producto.id',
            '=',
            'productos.presentacion'
        )->where(
            'productos.codigo',
            '=',
            $id
        )->get();

        return Response::json($api_result);
    }
    public function getProductoDataNombre($id){
        $api_result = Producto::select(
            'productos.*',
            'presentaciones_producto.presentacion'
        )->join(
            'presentaciones_producto',
            'presentaciones_producto.id',
            '=',
            'productos.presentacion'
        )->where(
            'productos.nombre_comercial',
            '=',
            $id
        )->where(
          'productos.estado',
          '=',
          '1'
          )->get();

        return Response::json($api_result);
    }
}
