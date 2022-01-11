<?php

namespace App\Http\Controllers;

use App\CuentaPagarDetalleAbono;
use App\CuentaPagarMaestro;
use App\CuentaPagarDetalleCompra;
use App\Events\ActualizacionBitacora;
use App\FormaPago;
use App\ingresoMaestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CuentasPagarController extends Controller
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
        return view('admin.cuentas_pagar.index');
    }

    /**
     * Show the form for creating a new resource.
     * This method is only used to create CuentaPagarDetalleAbono
     * objects since CuentaPagarMaestro objects are automatically
     * created and don't need a form
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $formas = FormaPago::select(
            'id',
            'nombre'
        )->where(
            'estado',
            '=',
            1
        )->get();
        return Response::json($formas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $maestro = CuentaPagarMaestro::find($request->cuentas_pagar_maestro_id);
        // dd($maestro->saldo > 0 && $request->monto <= $maestro->saldo);
        if ($maestro->saldo > 0 && $request->monto <= $maestro->saldo) {

            $date = date_format(date_create($request->fecha_ingreso), "Y-m-d");
            $cpda = CuentaPagarDetalleAbono::create([
                'fecha_ingreso' => $date,
                'monto' => $request->monto,
                'cuentas_pagar_maestro_id' => $request->cuentas_pagar_maestro_id,
                'forma_pago' => $request->forma_pago,
                'no_documento' => $request->no_documento,
                'user_id' => $request->user
            ]);

            $cuentas = CuentaPagarDetalleCompra::where('ingreso_id', '=', $request->factura)->get()->first();
            $cuentas->pago_factura = 2;
            $cuentas->save();

            event(new ActualizacionBitacora($cpda->id, Auth::user()->id, 'Creación', '', $cpda, 'cuentas_pagar_detalle_abono'));
            $maestro->decrement('saldo', $request->monto);
            return Response::json(['success'=>'éxito', 'saldo' => $maestro->saldo]);
        }else{
            return Response::json(['errors'=>'fail'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd($id);
        $cuenta = CuentaPagarMaestro::select(
            'cuentas_pagar_maestro.id',
            'cuentas_pagar_maestro.fecha_ingreso as fecha',
            'cuentas_pagar_maestro.saldo',
            'proveedores.nombre_comercial as proveedor'
        )->join(
            'proveedores',
            'cuentas_pagar_maestro.id_proveedor',
            '=',
            'proveedores.id'
        )->where(
            'cuentas_pagar_maestro.id',
            '=',
            $id
        )->where(
            'cuentas_pagar_maestro.id_proveedor',
            '<>',
             0
        )->get();

        return view('admin.cuentas_pagar.show', compact('cuenta'));
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

    public function getJson(Request $params){
        $api_result['data'] = CuentaPagarMaestro::select(
            'cuentas_pagar_maestro.id',
            'cuentas_pagar_maestro.fecha_ingreso as fecha',
            'cuentas_pagar_maestro.saldo',
            'proveedores.nombre_comercial as proveedor'
        )->join(
            'proveedores',
            'cuentas_pagar_maestro.id_proveedor',
            '=',
            'proveedores.id'
        )->where(
            'cuentas_pagar_maestro.id_proveedor',
            '<>',
            0
        )->get();
        return Response::json($api_result);
    }

    public function getDetalles(Request $request, $id){
        $compras = CuentaPagarMaestro::select(
            'ingresos_maestro.id as id_c',
            'cpdc.fecha_ingreso',
            DB::raw('CONCAT(ingresos_maestro.serie_factura, ingresos_maestro.num_factura) as no_compra'),
            'ingresos_maestro.total_ingreso as monto'
        )->join(
            'cuentas_pagar_detalle_compra as cpdc',
            'cpdc.cuentas_pagar_maestro_id',
            '=',
            'cuentas_pagar_maestro.id'
        )->join(
            'ingresos_maestro',
            'cpdc.ingreso_id',
            '=',
            'ingresos_maestro.id'
        )->where([
           ['cuentas_pagar_maestro.id','=',$id],
           ['cpdc.estado', '=', 1]
        ])->get()->toArray();
        for ($i = 0; $i < sizeOf($compras); $i++) {
            $compras[$i]['tipo'] = 'Compra';
        }

        $abonos = CuentaPagarMaestro::select(
            'cpda.fecha_ingreso',
            'cpda.monto as monto',
            'cpda.no_documento as no_compra',
            'formas_pago.nombre as forma_pago',
            'cpda.id as id_a'
        )->join(
            'cuentas_pagar_detalle_abono as cpda',
            'cpda.cuentas_pagar_maestro_id',
            '=',
            'cuentas_pagar_maestro.id'
        )->join(
            'formas_pago',
            'cpda.forma_pago',
            '=',
            'formas_pago.id'
        )->where([
            ['cuentas_pagar_maestro.id','=',$id],
            ['cpda.estado','=',1]
        ])->get()->toArray();
        //this adds the type attribute
        for ($i = 0; $i < sizeOf($abonos); $i++) {
            $abonos[$i]['tipo'] = 'Abono';
        }

        //I'm merging the two arrays to send the data
        $api_result['data'] = array_merge($compras, $abonos);
        return Response::json($api_result);
    }

    public function destroyAbono($id){
        $abono = CuentaPagarDetalleAbono::find($id);
        $maestro = CuentaPagarMaestro::find($abono->cuentas_pagar_maestro_id);

        $maestro->increment('saldo', $abono->monto);
        $abono->update(['estado'=> 2]);
        event(new ActualizacionBitacora($abono->id, Auth::user()->id, 'Eliminación', '', '', 'cuentas_pagar_detalle_abono'));

        return Response::json(['success' => 'éxito', 'saldo' => $maestro->saldo]);
    }

    public function monto($id){
      $api_result = ingresoMaestro::select(
            'total_ingreso'
        )->where(
          'id' ,
          '=',
          $id
          )->get();
      return Response::json($api_result);
    }

    public function facturas($id){
      $compras = CuentaPagarMaestro::select(
          'ingresos_maestro.id as id_c',
          'cpdc.fecha_ingreso',
          DB::raw('CONCAT(ingresos_maestro.serie_factura, ingresos_maestro.num_factura) as no_compra'),
          'ingresos_maestro.num_factura as factura',
          'ingresos_maestro.total_ingreso as monto',
          'cpdc.id as id'
      )->join(
          'cuentas_pagar_detalle_compra as cpdc',
          'cpdc.cuentas_pagar_maestro_id',
          '=',
          'cuentas_pagar_maestro.id'
      )->join(
          'ingresos_maestro',
          'cpdc.ingreso_id',
          '=',
          'ingresos_maestro.id'
      )->where([
         ['cuentas_pagar_maestro.id','=',$id],
         ['cpdc.estado', '=', 1],
         ['cpdc.pago_factura', '=', 1]
      ])->get();
      return Response::json($compras);
    }
}
