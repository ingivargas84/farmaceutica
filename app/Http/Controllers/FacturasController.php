<?php

namespace App\Http\Controllers;

use App\Events\ActualizacionBitacora;
use App\Factura;
use App\PedidoMaestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Luecano\NumeroALetras\NumeroALetras;
class FacturasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.facturas.index');
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
        $fechaFac = date_format(date_create_from_format('m/d/Y', $request->fecha_fac), 'Y-m-d');
        $Factura = Factura::create([
          'fecha_factura' => $fechaFac,
          'serie_factura' => $request->serie,
          'no_factura' => $request->factura,
          'subtotal' => $request->subtotal,
          'impuestos' => $request->impuesto,
          'total' => $request->total,
          'pedido_maestro_id' => $request->pedido1,
          'nit' => $request->nit,
          'direccion' => $request->direccion,
          'nombre_factura' => $request->cliente,
        ]);
        $Factura->save();

        event(new ActualizacionBitacora($Factura->id, Auth::user()->id, 'Creación', '', $Factura, 'factura'));

      //  return redirect()->route('facturas.index')->withFlash('La Factura se ha registrado exitosamente');
      return Response::json(['success'=>'éxito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $factura = Factura::select(
            'no_factura',
            'nombre_cliente as cliente',
            'nombre_factura as cliente_factura',
            'fecha_factura as fecha',
            'serie_factura', 'facturas.total',
            'facturas.estado',
            'facturas.id',
            'facturas.subtotal',
            'facturas.impuestos',
            'facturas.motivo_anulacion'
        )->join(
            'pedidos_maestro',
            'facturas.pedido_maestro_id',
            'pedidos_maestro.id'
        )->join(
            'clientes',
            'pedidos_maestro.cliente_id',
            'clientes.id'
        )->where(
            'facturas.id',
            '=',
            $id
        )->get();

        return view('admin.facturas.show', compact('factura'));
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
    public function destroy($id, Request $request)
    {
        $factura = Factura::find($id);

        $factura->update([
            'estado' => 2,
            'motivo_anulacion' => $request->motivo_anulacion]);

            event(new ActualizacionBitacora($factura->id, Auth::user()->id, 'Anulación', '', '', 'facturas'));

            return Response::json(['success' => 'éxito'], 200);

    }

    public function getJson(){
        $data['data'] = Factura::select(
            'no_factura',
            'serie_factura',
            'nombre_cliente as cliente',
            'fecha_factura as fecha',
            'facturas.total',
            'facturas.estado',
            'facturas.id',
            'nombre_factura as cliente_factura'
        )->join(
            'pedidos_maestro',
            'facturas.pedido_maestro_id',
            'pedidos_maestro.id'
        )->join(
            'clientes',
            'pedidos_maestro.cliente_id',
            'clientes.id'
        )->get();

        return Response::json($data);
    }

    public function getPedidos(){
       $query = " SELECT *
                  FROM pedidos_maestro
                  WHERE (id NOT IN (SELECT pedido_maestro_id FROM facturas) or id IN (SELECT pedido_maestro_id FROM facturas where estado = 2) and id NOT IN (SELECT pedido_maestro_id FROM facturas WHERE estado = 1))
                  AND estado_facturacion = 2";
       $pedidos= DB::select($query);
       return Response::json($pedidos);
    }

    public function InformacionCliente($id){
      $cliente = PedidoMaestro::select(
            'pedidos_maestro.total as total',
            'clientes.nit as  nit',
            'clientes.nombre_cliente',
            'clientes.direccion as direccion'
        )->join(
            'clientes',
            'clientes.id',
            '=',
            'pedidos_maestro.cliente_id'
          )->where(
              'pedidos_maestro.id',
              '=',
              $id
            )->get();
        return Response::json($cliente);
    }

    public function noFacturaDisponible(){
       $factura = Input::get("factura");
       $query = Factura::where('no_factura', $factura)->get();
       $contador = count($query);
       if ($contador == 0) {
           return 'false';
       } else {
           return 'true';
       }
    }

    public function noSerieDisponible(){
       $serie = Input::get("serie");
       $factura = Input::get("factura");
       $f = $serie.$factura;
       $query = "SELECT concat_ws('', serie_factura, no_factura) FROM facturas
       WHERE (concat_ws('', serie_factura, no_factura)) = '$f'";
       $facturas = DB::select($query);
       $contador = count($facturas);
       if ($contador == 0) {
           return 'false';
       } else {
           return 'true';
       }

    }

        public function NuevaFactura($id){
          $query = "SELECT pedidos_detalle.id, pedidos_detalle.cantidad, pedidos_detalle.precio, pedidos_detalle.subtotal, productos.nombre_comercial as producto,
          productos.codigo
          FROM pedidos_detalle
          INNER JOIN pedidos_maestro on pedidos_detalle.pedido_maestro_id = pedidos_maestro.id
          INNER JOIN facturas ON facturas.pedido_maestro_id = pedidos_maestro.id
          INNER JOIN productos ON productos.id = pedidos_detalle.producto_id
          WHERE pedidos_detalle.estado = 1 AND facturas.id = $id ";
          $detalles = DB::select($query);

          $encabezado = Factura::select(
              'facturas.id',
              'facturas.fecha_factura',
              'facturas.serie_factura',
              'facturas.no_factura',
              'facturas.subtotal',
              'facturas.impuestos',
              'facturas.total',
              'clientes.id as cliente_id',
              'clientes.nombre_cliente',
              'clientes.nit',
              'clientes.direccion',
              'clientes.dias_credito',
              'clientes.telefono_compras',
              'users.name as vendedor'
          )->join(
              'pedidos_maestro',
              'facturas.pedido_maestro_id',
              'pedidos_maestro.id'
          )->join(
              'clientes',
              'pedidos_maestro.cliente_id',
              'clientes.id'
          )->join(
              'users',
              'users.id',
              'pedidos_maestro.user_id'
          )->where(
              'facturas.id',
              '=',
              $id
          )->get();
          $cantidad = $encabezado[0]->total;
          $formatter = new NumeroALetras;
          $ver = $formatter->toMoney($cantidad,2,'Quetzales', 'Centavos');

          $pdf = \PDF::loadView('admin.facturas.factura', compact('detalles', 'encabezado', 'ver'))->setPaper(array(0, 0, 396, 612), 'portrait');;
          return $pdf->download('factura.pdf');
        }
}
