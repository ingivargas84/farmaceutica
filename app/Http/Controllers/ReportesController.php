<?php

namespace App\Http\Controllers;

use App\Bodega;
use App\Cliente;
use App\MovimientoProducto;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use App\Visita;
use App\PedidoMaestro;
use App\User;
use App\Proveedor;
use App\TraspasoBodega;
use App\territorios;
use Illuminate\Support\Facades\Auth;

class ReportesController extends Controller
{

    /**
     * minMaxJson
     *
     * returns a JSON with all products
     * that are in a range of ±10 of their
     * maxmimum or minimum posible stock.
     *
     * @return Response::json
     */
    function minMaxJson()
    {
        //MAX ±10
        $max = Producto::select(
            'codigo',
            'nombre_comercial',
            'stock_maximo',
            DB::raw('SUM(movimientos_producto.existencias) as existencias')
        )->join(
            'movimientos_producto',
            'movimientos_producto.producto_id',
            '=',
            'productos.id'
        )->groupBy(
            'productos.codigo',
            'productos.nombre_comercial',
            'productos.stock_maximo'
        )->havingRaw(
            'SUM(movimientos_producto.existencias) >= stock_maximo - 10 ' .
                'and SUM(movimientos_producto.existencias) <= stock_maximo + 10'
        )->where(
            'productos.estado',
            '=',
            1
        )->orderBy(
            'productos.nombre_comercial',
            'asc'
        )->get();

        //MIN ±10
        $min = Producto::select(
            'codigo',
            'nombre_comercial',
            'stock_minimo',
            DB::raw('SUM(movimientos_producto.existencias) as existencias')
        )->join(
            'movimientos_producto',
            'movimientos_producto.producto_id',
            '=',
            'productos.id'
        )->groupBy(
            'productos.codigo',
            'productos.nombre_comercial',
            'productos.stock_minimo'
        )->havingRaw(
            'SUM(movimientos_producto.existencias) >= stock_minimo - 10 ' .
                'and SUM(movimientos_producto.existencias) <= stock_minimo + 10'
        )->where(
            'productos.estado',
            '=',
            1
        )->orderBy(
            'productos.nombre_comercial',
            'asc'
        )->get();

        $data = array(['max' => $max, 'min' => $min]);
        // dd(json_encode($data));
        return Response::json($data[0]);
    }

    /**
     * stockJson
     *
     * Returns a JSON file with all the
     * products currently active together
     * with theit current stock and total
     * value.
     *
     * @return Response::json
     */
    function stockJson()
    {
        $data = Producto::select(
            'codigo',
            'nombre_comercial',
            DB::raw('SUM(movimientos_producto.existencias) as existencias'),
            'precio_venta',
            DB::raw('SUM(existencias * productos.precio_venta) as subtotal')
        )->join(
            'movimientos_producto',
            'movimientos_producto.producto_id',
            '=',
            'productos.id'
        )->groupBy(
            'productos.codigo',
            'productos.nombre_comercial',
            'productos.precio_venta'
        )->where(
            'productos.estado',
            '=',
            '1'
        )->orderBy(
            'productos.nombre_comercial',
            'asc'
        )->get();

        return Response::json($data);
    }

    function warehouseStockJson()
    {
        $bodegas = Bodega::select('id', 'nombre')->get();

        $warehouse_stock = [];

        foreach ($bodegas as $bodega) {
            $stock = Producto::select(
                'codigo',
                'nombre_comercial',
                DB::raw('SUM(movimientos_producto.existencias) as existencias'),
                'precio_venta',
                DB::raw('SUM(existencias * productos.precio_venta) as subtotal')
            )->join(
                'movimientos_producto',
                'movimientos_producto.producto_id',
                '=',
                'productos.id'
            )->groupBy(
                'productos.codigo',
                'productos.nombre_comercial',
                'productos.precio_venta'
            )->where([
                ['productos.estado', '=', '1'],
                ['movimientos_producto.bodega_id', '=', $bodega->id]
            ])->orderBy(
                'productos.nombre_comercial',
                'asc'
            )->get();
            $warehouse_stock[$bodega->nombre] = $stock;
        }

        return Response::json($warehouse_stock);
    }



    function warehouseStockVendedorJson()
    {
        $bodegas = Bodega::select('bodegas.id as id', 'bodegas.nombre as nombre',
                      'users.name as name1',
                      DB::raw('CONCAT("Bodega: ", users.name) as name'))
                    ->join(
                      'users',
                      'users.id',
                      '=',
                      'bodegas.user_id'
                      )
                    ->where('user_id', '=', Auth::user()->id)->get();

        $warehouse_stock = [];

        foreach ($bodegas as $bodega) {
            $stock = Producto::select(
                'codigo',
                'nombre_comercial',
                DB::raw('SUM(movimientos_producto.existencias) as existencias'),
                'precio_venta',
                DB::raw('SUM(existencias * productos.precio_venta) as subtotal')
            )->join(
                'movimientos_producto',
                'movimientos_producto.producto_id',
                '=',
                'productos.id'
            )->groupBy(
                'productos.codigo',
                'productos.nombre_comercial',
                'productos.precio_venta'
            )->where([
                ['productos.estado', '=', '1'],
                ['movimientos_producto.bodega_id', '=', $bodega->id]
            ])->orderBy(
                'productos.nombre_comercial',
                'asc'
            )->get();
            //$warehouse_stock[$bodega->nombre] = [$stock, $bodega->name => $stock];
            $warehouse_stock[$bodega ->name] = $stock;
            $warehouse_stock[$bodega->nombre] = $stock;


        }

        return Response::json($warehouse_stock);
    }

    function expirationJson()
    {
        $data = MovimientoProducto::select(
            'caducidad',
            'codigo',
            'nombre_comercial',
            DB::raw('SUM(movimientos_producto.existencias) as existencias'),
            DB::raw('ABS(datediff(curdate(), caducidad)) as days_to_expire')
        )->join(
            'productos',
            'productos.id',
            '=',
            'movimientos_producto.producto_id'
        )->groupBy(
            'caducidad',
            'codigo',
            'nombre_comercial'
        )->havingRaw(
            'caducidad between curdate()' .
                'and adddate(curdate(), interval 90 day)'
        )->orderBy(
            'nombre_comercial',
            'asc'
        )->get();

        return Response::json($data);
    }

    function clientBalanceJson($date)
    {

        //this array will contain the returned data
        $data = array();
        //get all the clients with accounts
        $clientes = Cliente::has('cuentaCobrarMaestro')->where('estado', 1)->where('id', '<>', 0)->get();
        $n = 0;
        //calculate the client balance from the given date back
        foreach ($clientes as $cliente) {
            $cuenta = $cliente->cuentaCobrarMaestro;
            //get all the orders
            $pedidos = $cuenta->cuentasCobrarDetallePedido->where('estado', 1)->where('fecha_ingreso', '<=',  $date);
            $total_pedidos = 0;

            //add the order details amount to the variable
            foreach ($pedidos as $pedido) {
                //here 'monto()' is a function
                $total_pedidos += $pedido->monto();
            }

            //get all the deposits
            $abonos = $cuenta->cuentasCobrarDetalleAbono->where('estado', 1)->where('fecha_ingreso', '<=',  $date);
            $total_abonos = 0;

            //add the deposit detail amount to the variable
            foreach ($abonos as $abono) {
                //here 'monto' is a property
                $total_abonos += $abono->monto;
            }

            $data[$n]['nombre_cliente'] = $cliente->nombre_cliente;
            $data[$n]['saldo'] = $total_pedidos - $total_abonos;
            $n++;
        }
        $n = 0;
        //if the balance is 0 or lower it deletes the element from the array
        foreach ($data as $key => $dat) {
            if ($dat['saldo'] <= 0) {
                unset($data[$key]);
            }
        }

        return Response::json($data);
    }


    function reporteSaldosTerritorios(Request $request)
    {
        //this array will contain the returned data
        $data = array();

        if ($request->territorios == "todos")
        {
          $territorio = "Todos";
        }
        else
        {
          $territorio = territorios::where('id',$request->territorios)->get()->first();
        }
        
        $fecha = Carbon::parse($request->fecha)->format('Y-m-d');

        if ($request->territorios == "todos"){
        //get all the clients with accounts
          $clientes = Cliente::has('cuentaCobrarMaestro')->where('estado', 1)->where('id', '<>', 0)->get();
        }
        else
        {
          $clientes = Cliente::has('cuentaCobrarMaestro')->where('estado', 1)->where('territorio',$request->territorios)->where('id', '<>', 0)->get();
        }

        $n = 0;
        $total = 0;
        //calculate the client balance from the given date back
        foreach ($clientes as $cliente) {
            $cuenta = $cliente->cuentaCobrarMaestro;
            //get all the orders
            $pedidos = $cuenta->cuentasCobrarDetallePedido->where('estado', 1)->where('fecha_ingreso', '<=',  $fecha);
            $total_pedidos = 0;

            //add the order details amount to the variable
            foreach ($pedidos as $pedido) {
                //here 'monto()' is a function
                $total_pedidos += $pedido->monto();
            }

            //get all the deposits
            $abonos = $cuenta->cuentasCobrarDetalleAbono->where('estado', 1)->where('fecha_ingreso', '<=',  $fecha);
            $total_abonos = 0;

            //add the deposit detail amount to the variable
            foreach ($abonos as $abono) {
                //here 'monto' is a property
                $total_abonos += $abono->monto;
            }

            $data[$n]['nombre_cliente'] = $cliente->nombre_cliente;
            $data[$n]['saldo'] = $total_pedidos - $total_abonos;
            $total = $total + $data[$n]['saldo'];
            $n++;
        }

        $n = 0;
        //if the balance is 0 or lower it deletes the element from the array
        foreach ($data as $key => $dat) {
            if ($dat['saldo'] <= 0) {
                unset($data[$key]);
            }
        }               

        $usu = User::where("id", Auth::user()->id)->get();
        $pdf = \PDF::loadView('admin.reportes.pdfSaldosTerritorios', compact('data','usu','fecha','territorio', 'total'));
        return $pdf->stream('ReporteSaldosTerritorios.pdf');

    }


    public function pdfVisitas(Request $request){

      $uss = Auth::user()->id;
      $query = "SELECT roles.id as id
      FROM users
            INNER JOIN model_has_roles ON users.id = model_id
            INNER JOIN roles ON roles.id = model_has_roles.role_id
            WHERE users.id =  $uss";

      $usua = DB::select($query);

      if ($usua[0]->id <= 2) {

        $fecha = date('y-m-d');

        $fecha1 = Carbon::parse($request->fecha)->format('Y-m-d');
        $fechaformateada = Carbon::parse($request->fecha)->format('d-m-Y');


          $visitas = DB::table('visitas')
          ->select('visitas.id as id', 
          'visitas.observaciones as observaciones', 
          DB::raw('IF(visitas.cliente_id>0, clientes.nombre_cliente, visitas.nombre_cliente) as nombre'),
          DB::raw('IF(visitas.cliente_id>0, clientes.direccion, visitas.direccion_cliente) as direccion'),
          'visitas.created_at as hora')
                    ->join('clientes', 'visitas.cliente_id', '=', 'clientes.id')
                    ->where('visitas.user_id', '=', $request->vendedor)
                    ->where('visitas.fecha', '=', $fecha1)
                    ->where('visitas.id', '>', 0)
                    ->get();

          $ventas = DB::table('pedidos_maestro')
                    ->where('user_id', '=', $request->vendedor)
                    ->where('fecha_ingreso', '=', $fecha1)
                    ->where('cliente_id', '<>', 0)
                    ->select(DB::raw('sum(total) as total'))
                    ->get();

            $abonos = DB::table('cuentas_cobrar_detalle_abono')
                      ->where('fecha_ingreso', '=', $fecha1)
                      ->select(DB::raw('sum(monto) as total'))
                      ->get();

           $usuario = User::where("id", $request->vendedor)->get();

      $pdf = \PDF::loadView('admin.reportes.pdfVisitas', compact('visitas', 'fechaformateada', 'ventas', 'abonos', 'usuario', 'usua'))->setPaper('a4', 'landscape');
      return $pdf->download('ReporteVisitas.pdf');

      }else {

         $fecha = date('y-m-d');

         $fecha1 = Carbon::parse($request->fecha)->format('Y-m-d');
         $fechaformateada = Carbon::parse($request->fecha)->format('d-m-Y');

            $visitas = DB::table('visitas')
                      ->join('clientes', 'visitas.cliente_id', '=', 'clientes.id')
                      ->where('user_id', '=', $request->vendedor)
                      ->where('fecha', '=', $fecha1)
                      ->where('clientes.id', '<>', 0)
                      ->select('visitas.id as id', 
                      'visitas.observaciones as observaciones', 
                      DB::raw('IF(visitas.cliente_id>0, clientes.nombre_cliente, visitas.nombre_cliente) as nombre'),
                      DB::raw('IF(visitas.cliente_id>0, clientes.direccion, visitas.direccion_cliente) as direccion')
                      )
                      ->get();

            $ventas = DB::table('pedidos_maestro')
                      ->where('user_id', '=', $request->vendedor)
                      ->where('fecha_ingreso', '=', $fecha1)
                      ->where('cliente_id', '<>', 0)
                      ->select(DB::raw('sum(total) as total'))
                      ->get();

              $abonos = DB::table('cuentas_cobrar_detalle_abono')
                        ->where('fecha_ingreso', '=', $fecha1)
                        ->select(DB::raw('sum(monto) as total'))
                        ->get();
             $usuario = User::where("id", $request->vendedor)->get();

        $pdf = \PDF::loadView('admin.reportes.pdfVisitas', compact('visitas', 'fechaformateada', 'ventas', 'abonos', 'usuario', 'usua'))->setPaper('a4', 'landscape');
        return $pdf->download('ReporteVisitas.pdf');
      }



    }

  public function pdfSaldoProveedores(Request $request){
    
    $fecha = Carbon::createFromFormat('Y-m-d', $request->fecha);
    $f = Carbon::createFromFormat('Y-m-d', $request->fecha);
    
    $proveedores = DB::table('ingresos_maestro')
        ->select('proveedores.nombre_comercial as proveedor', DB::raw('sum(ingresos_maestro.total_ingreso) as total_ingreso'))
        ->join('proveedores', 'proveedores.id', '=', 'ingresos_maestro.proveedor_id')
        ->where('ingresos_maestro.fecha_compra', '<=', $fecha)                                          
        ->where('proveedores.id', '>', 0)
        ->where('ingresos_maestro.estado_ingreso', '=', 1)
        ->groupby('proveedores.nombre_comercial')
        ->get();

    $usu = User::where("id", Auth::user()->id)->get() ;
    $pdf = \PDF::loadView('admin.reportes.pdfProveedores', compact('proveedores', 'f', 'usu'));
    return $pdf->download('ReporteSaldoProveedores.pdf');
  }

  public function getUsuarios(){
    $usuario = User::where("estado", 1)
                    ->where("id",">=",5)->get() ;
    return Response::json($usuario);
  }



  public function pdfVentasVendedor(Request $request){

    if ($request->usuario == "todos") {
              if ($request->territorio == "todos") {
                $fecha = date('y-m-d');

                $fechaInicial = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
                $fechaFinal = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);
                $fechaInicialf = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
                $fechaFinalf = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);


                $query = " SELECT pedidos_maestro.fecha_ingreso as fecha, pedidos_maestro.no_pedido as pedido, clientes.nombre_cliente as cliente,
                            pedidos_maestro.total as monto, users.name as vendedor, territorios.territorio as territorio
                            FROM pedidos_maestro
                            INNER JOIN clientes  ON pedidos_maestro.cliente_id = clientes.id
                            INNER JOIN users  ON users.id = pedidos_maestro.user_id
                            INNER JOIN territorios ON clientes.territorio = territorios.id
                            WHERE  pedidos_maestro.fecha_ingreso BETWEEN '$request->fechaInicial' AND '$request->fechaFinal'
                            AND pedidos_maestro.estado = 1
                            AND clientes.id <> 0
                            Order by pedidos_maestro.fecha_ingreso asc";
                $tabla= DB::select($query);
              

                $queryB =" SELECT SUM(monto) as total
                           FROM cuentas_cobrar_detalle_abono
                           WHERE fecha_ingreso  BETWEEN '$fechaInicial' AND '$fechaFinal' and estado = 1";
                $abonos = DB::select($queryB);
                $usuario = User::where("id", $request->usuario)->get() ;
                $usu = User::where("id", Auth::user()->id)->get() ;
                $contador = count($usuario);
                $territorio = territorios::where("id", '=' , $request->territorio)->where('id', '<>' , 0)->get();
                $terri = count($territorio);
                $pdf = \PDF::loadView('admin.reportes.pdfVentasVendedor', compact('tabla', 'abonos', 'usuario','fechaInicialf', 'fechaFinalf', 'usu', 'contador', 'territorio', 'terri'))->setPaper('a4', 'landscape');
                return $pdf->download('ReporteVentasVendedor.pdf');

              }else {

                $fecha = date('y-m-d');

                $fechaInicial = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
                $fechaFinal = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);
                $fechaInicialf = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
                $fechaFinalf = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);

                $query = " SELECT pedidos_maestro.fecha_ingreso as fecha, pedidos_maestro.no_pedido as pedido, clientes.nombre_cliente as cliente,
                            pedidos_maestro.total as monto, users.name as vendedor, territorios.territorio as territorio
                            FROM pedidos_maestro
                            INNER JOIN clientes  ON pedidos_maestro.cliente_id = clientes.id
                            INNER JOIN users  ON users.id = pedidos_maestro.user_id
                            INNER JOIN territorios ON clientes.territorio = territorios.id
                            WHERE  pedidos_maestro.fecha_ingreso BETWEEN '$fechaInicial' AND '$fechaFinal'
                            AND pedidos_maestro.estado = 1
                            AND clientes.id <> 0
                            AND  territorios.id = $request->territorio
                            order by pedidos_maestro.fecha_ingreso asc";
                $tabla= DB::select($query);
              
                $queryB ="SELECT SUM(monto) as total
                          FROM cuentas_cobrar_detalle_abono
                          INNER JOIN cuentas_cobrar_maestro ON cuentas_cobrar_maestro.id = cuentas_cobrar_detalle_abono.cuentas_cobrar_maestro_id
                          INNER JOIN clientes on cuentas_cobrar_maestro.id_cliente = clientes.id
                          INNER JOIN territorios ON clientes.territorio = territorios.id
                          WHERE cuentas_cobrar_detalle_abono.fecha_ingreso  BETWEEN '$fechaInicial'
                          AND '$fechaFinal'
                          and cuentas_cobrar_detalle_abono.estado = 1
                          AND territorios.id = $request->territorio";
                $abonos = DB::select($queryB);
                $usuario = User::where("id", $request->usuario)->get() ;
                $usu = User::where("id", Auth::user()->id)->get() ;
                $contador = count($usuario);
                $territorio = territorios::where("id", '=' , $request->territorio)->where('id', '<>' , 0)->get();
                $terri = count($territorio);
                $pdf = \PDF::loadView('admin.reportes.pdfVentasVendedor', compact('tabla', 'abonos', 'usuario','fechaInicialf', 'fechaFinalf', 'usu', 'contador', 'territorio', 'terri'))->setPaper('a4', 'landscape');
                return $pdf->download('ReporteVentasVendedor.pdf');
              }

    }else {
          if ($request->territorio == "todos") {
            $fecha = date('y-m-d');

            $fechaInicial = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
            $fechaFinal = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);
            $fechaInicialf = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
            $fechaFinalf = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);

            $query = " SELECT pedidos_maestro.fecha_ingreso as fecha, pedidos_maestro.no_pedido as pedido, clientes.nombre_cliente as cliente,
                        pedidos_maestro.total as monto, territorios.territorio as territorio
                        FROM pedidos_maestro
                        INNER JOIN clientes  ON pedidos_maestro.cliente_id = clientes.id
                        INNER JOIN users  ON users.id = pedidos_maestro.user_id
                        INNER JOIN territorios ON clientes.territorio = territorios.id
                        WHERE users.id = $request->usuario and pedidos_maestro.fecha_ingreso BETWEEN '$fechaInicial' AND '$fechaFinal'
                        AND pedidos_maestro.estado = 1
                        AND clientes.id <> 0";
            $tabla= DB::select($query);
        

           $queryB =" SELECT SUM(monto) as total
                      FROM cuentas_cobrar_detalle_abono
                      INNER JOIN users ON users.id = cuentas_cobrar_detalle_abono.user_id
                      WHERE cuentas_cobrar_detalle_abono.fecha_ingreso  BETWEEN '$fechaInicial' AND '$fechaFinal'
                       and cuentas_cobrar_detalle_abono.estado = 1 and users.id = $request->usuario";
          $abonos = DB::select($queryB);
            $usuario = User::where("id", $request->usuario)->get() ;
            $usu = User::where("id", Auth::user()->id)->get() ;
            $contador = count($usuario);
            $territorio = territorios::where("id", '=' , $request->territorio)->where('id', '<>' , 0)->get();
            $terri = count($territorio);
            $pdf = \PDF::loadView('admin.reportes.pdfVentasVendedor', compact('tabla', 'abonos', 'usuario','fechaInicialf', 'fechaFinalf', 'usu', 'contador', 'territorio', 'terri'))->setPaper('a4', 'landscape');
            return $pdf->download('ReporteVentasVendedor.pdf');

          }else {

            $fecha = date('y-m-d');

            $fechaInicial = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
            $fechaFinal = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);
            $fechaInicialf = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
            $fechaFinalf = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);

            $query = " SELECT pedidos_maestro.fecha_ingreso as fecha, pedidos_maestro.no_pedido as pedido, clientes.nombre_cliente as cliente,
                        pedidos_maestro.total as monto, territorios.territorio as territorio
                        FROM pedidos_maestro
                        INNER JOIN clientes  ON pedidos_maestro.cliente_id = clientes.id
                        INNER JOIN users  ON users.id = pedidos_maestro.user_id
                        INNER JOIN territorios ON clientes.territorio = territorios.id
                        WHERE users.id = $request->usuario and pedidos_maestro.fecha_ingreso BETWEEN '$fechaInicial' AND '$fechaFinal'
                        AND pedidos_maestro.estado = 1
                        AND clientes.id <> 0
                        AND  territorios.id = $request->territorio";
            $tabla= DB::select($query);
        

           $queryB =" SELECT SUM(monto) as total
                      FROM cuentas_cobrar_detalle_abono
                      INNER JOIN users ON users.id = cuentas_cobrar_detalle_abono.user_id
                      INNER JOIN cuentas_cobrar_maestro ON cuentas_cobrar_maestro.id = cuentas_cobrar_detalle_abono.cuentas_cobrar_maestro_id
                      INNER JOIN clientes on cuentas_cobrar_maestro.id_cliente = clientes.id
                      INNER JOIN territorios ON clientes.territorio = territorios.id
                      WHERE cuentas_cobrar_detalle_abono.fecha_ingreso  BETWEEN '$fechaInicial' AND '$fechaFinal'
                      and cuentas_cobrar_detalle_abono.estado = 1 and users.id = $request->usuario
                      AND territorios.id = $request->territorio";
          $abonos = DB::select($queryB);
            $usuario = User::where("id", $request->usuario)->get() ;
            $usu = User::where("id", Auth::user()->id)->get() ;
            $contador = count($usuario);
            $territorio = territorios::where("id", '=' , $request->territorio)->where('id', '<>' , 0)->get();
            $terri = count($territorio);
            $pdf = \PDF::loadView('admin.reportes.pdfVentasVendedor', compact('tabla', 'abonos', 'usuario','fechaInicialf', 'fechaFinalf', 'usu', 'contador', 'territorio' , 'terri'))->setPaper('a4', 'landscape');
            return $pdf->download('ReporteVentasVendedor.pdf');
          }

    }
  }

  public function getProveedores(){
    $proveedor = Proveedor::where("estado", 1)->get() ;
    return Response::json($proveedor);
  }

  public function reporteComprasProveedores(Request $request){
    if ($request->proveedor == "todos") {
      $c = 1;
      $ca = "";
      $fecha = date('y-m-d');

      $fechaInicial = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
      $fechaFinal = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);
      $fechaInicialf = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
      $fechaFinalf = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);

      $query = "SELECT proveedores.nombre_comercial as proveedor, bodegas.nombre as bodega, ingresos_maestro.num_factura as factura,
                ingresos_maestro.total_ingreso as monto
                from ingresos_maestro
                INNER JOIN bodegas on bodegas.id = ingresos_maestro.bodega_id
                INNER JOIN proveedores on proveedores.id = ingresos_maestro.proveedor_id
                WHERE ingresos_maestro.fecha_compra  BETWEEN '$fechaInicial' AND '$fechaFinal'
                AND proveedores.id <> 0
                AND ingresos_maestro.estado_ingreso = 1";
        $tabla= DB::select($query);
        $usu = User::where("id", Auth::user()->id)->get() ;
        $pdf = \PDF::loadView('admin.reportes.pdfCompras', compact('tabla', 'usu', 'c', 'fechaInicialf', 'fechaFinalf', 'ca'));
        return $pdf->download('ReporteComprasProveedores.pdf');

    }else {

      $fecha = date('y-m-d');

      $fechaInicial = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
      $fechaFinal = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);
      $fechaInicialf = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
      $fechaFinalf = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);

      $query = "SELECT proveedores.nombre_comercial as proveedor, bodegas.nombre as bodega, ingresos_maestro.num_factura as factura,
                ingresos_maestro.total_ingreso as monto
                from ingresos_maestro
                INNER JOIN bodegas on bodegas.id = ingresos_maestro.bodega_id
                INNER JOIN proveedores on proveedores.id = ingresos_maestro.proveedor_id
                WHERE   ingresos_maestro.proveedor_id = $request->proveedor and ingresos_maestro.fecha_compra BETWEEN '$fechaInicial' AND '$fechaFinal'
                AND proveedores.id <> 0
                AND ingresos_maestro.estado_ingreso = 1";
        $tabla= DB::select($query);
        $ca = Proveedor::where("id", $request->proveedor)->get() ;
        $c =0;
        $usu = User::where("id", Auth::user()->id)->get() ;
        $pdf = \PDF::loadView('admin.reportes.pdfCompras', compact('tabla', 'usu', 'c', 'fechaInicialf', 'fechaFinalf', 'ca'));
        return $pdf->download('ReporteComprasProveedores.pdf');
    }
  }

  public function pdfTraspasoBodegas(Request $request){
    
    $fechaInicial = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
    $fechaFinal = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);
    $fechaInicialf = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
    $fechaFinalf = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);

      $query = " SELECT traspasos_bodega.id , traspasos_bodega.cantidad, b1.nombre as bodega_origen, b2.nombre as bodega_destino, productos.nombre_comercial as producto, users.name as user, productos.precio_venta as precio
              from traspasos_bodega
              INNER JOIN bodegas as b1 on b1.id = traspasos_bodega.bodega_origen
              INNER JOIN bodegas as b2 on b2.id = traspasos_bodega.bodega_destino
              INNER JOIN productos on productos.id = traspasos_bodega.producto_id
              INNER JOIN users on users.id = traspasos_bodega.user_id
              WHERE traspasos_bodega.created_at BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
    $bodegas = DB::select($query);
    $usu = User::where("id", Auth::user()->id)->get() ;
    $pdf = \PDF::loadView('admin.reportes.pdfTraspasoBodega', compact('bodegas',  'fechaInicialf', 'fechaFinalf', 'usu'))->setPaper('a4', 'landscape');
    return $pdf->download('ReporteTraspasoBodegas.pdf');
  }

  public function getTerritorios(){
    $territorios = territorios::where("estado", 1)->where('id', '<>', '0')->get() ;
    return Response::json($territorios);
  }

  public function reporteMes(Request $request){
      $warehouse_stock = [];
      $warehouse_stock1 = [];
      $warehouse_stock2 = [];
      $warehouse_stock3 = [];
      $anio = $request->anio;
      $mes = $request->mes;
      $dias = $request->dias;
    for($i=1; $i<=$request->dias; $i++)
    {
      $query ="SELECT SUM(pedidos_maestro.total) as ventas, pedidos_maestro.fecha_ingreso as fecha
                from pedidos_maestro
                WHERE pedidos_maestro.fecha_ingreso = '$request->anio-$request->mes-$i'
                AND pedidos_maestro.cliente_id <> 0
                AND pedidos_maestro.estado = 1
                GROUP BY pedidos_maestro.fecha_ingreso";
                $ventas = DB::select($query);
                $warehouse_stock[$i] = $ventas;
     $query1 = "SELECT SUM(cuentas_cobrar_detalle_abono.monto) as abono, cuentas_cobrar_detalle_abono.fecha_ingreso as fecha
                FROM cuentas_cobrar_detalle_abono
                INNER JOIN cuentas_cobrar_maestro ON cuentas_cobrar_maestro.id = cuentas_cobrar_detalle_abono.cuentas_cobrar_maestro_id
                WHERE cuentas_cobrar_detalle_abono.fecha_ingreso = '$request->anio-$request->mes-$i'
                AND cuentas_cobrar_maestro.id_cliente <> 0
                AND cuentas_cobrar_detalle_abono.estado = 1
                GROUP BY cuentas_cobrar_detalle_abono.fecha_ingreso";
                $abonos = DB::select($query1);
                $warehouse_stock1[$i] = $abonos;
    $query2 = "SELECT SUM(ingresos_maestro.total_ingreso) as compras
                FROM ingresos_maestro
                WHERE ingresos_maestro.fecha_compra = '$request->anio-$request->mes-$i'
                AND ingresos_maestro.proveedor_id <> 0
                AND ingresos_maestro.estado_ingreso = 1
                GROUP BY ingresos_maestro.fecha_compra";
               $compras = DB::select($query2);
               $warehouse_stock2[$i] = $compras;
    $query3 = "SELECT sum(cuentas_pagar_detalle_abono.monto) as abonos
              FROM cuentas_pagar_detalle_abono
              INNER JOIN cuentas_pagar_maestro ON cuentas_pagar_maestro.id = cuentas_pagar_detalle_abono.cuentas_pagar_maestro_id
              WHERE cuentas_pagar_detalle_abono.fecha_ingreso = '$request->anio-$request->mes-$i'
              AND cuentas_pagar_maestro.id_proveedor <> 0
              AND cuentas_pagar_detalle_abono.estado = 1
              GROUP BY cuentas_pagar_detalle_abono.fecha_ingreso";
               $abonop = DB::select($query3);
               $warehouse_stock3[$i] = $abonop;

    }
      $usu = User::where("id", Auth::user()->id)->get() ;
      $pdf = \PDF::loadView('admin.reportes.pdfMensual', compact('warehouse_stock','warehouse_stock1','warehouse_stock2','warehouse_stock3','anio', 'mes', 'dias', 'usu' ));
  return $pdf->download('ReporteVentasComprasMensual.pdf');
// return Response::json($warehouse_stock1);
  }

  public function liquidacionMensual(Request $request){
    $mes = $request->mes;
      $pedidos = PedidoMaestro::select(
          'pedidos_maestro.fecha_ingreso as fecha',
          'clientes.nombre_cliente as clientes',
          'pedidos_maestro.no_pedido as pedido',
          'pedidos_maestro.total as total'
        )->join(
          'clientes',
          'clientes.id',
          '=',
          'pedidos_maestro.cliente_id'
          )->where(
            'pedidos_maestro.user_id',
            '=',
            $request->usuarioLiquidacion
            )->whereRaw(
                  'MONTH(pedidos_maestro.fecha_ingreso) = ?', [$request->mes]
              )->where(
                'pedidos_maestro.estado',
                '=',
                1
                )->where(
                  'clientes.id',
                  '<>',
                  0
                  )->get();
            $query = "SELECT cuentas_cobrar_detalle_abono.fecha_ingreso as fecha,
            clientes.nombre_cliente as cliente,
            formas_pago.nombre as metodo,
            cuentas_cobrar_detalle_abono.monto as total
            FROM cuentas_cobrar_detalle_abono
            INNER JOIN cuentas_cobrar_maestro ON cuentas_cobrar_maestro.id = cuentas_cobrar_detalle_abono.cuentas_cobrar_maestro_id
            INNER JOIN clientes ON clientes.id = cuentas_cobrar_maestro.id_cliente
            INNER JOIN formas_pago ON formas_pago.id = cuentas_cobrar_detalle_abono.forma_pago
            WHERE cuentas_cobrar_detalle_abono.user_id = $request->usuarioLiquidacion
            AND MONTH(cuentas_cobrar_detalle_abono.fecha_ingreso) = $request->mes
            AND clientes.id <> 0
            AND cuentas_cobrar_detalle_abono.estado = 1";
            $abonos = DB::select($query);

            $queryusu = "SELECT users.name as usuario
                          FROM users
                          WHERE users.id = $request->usuarioLiquidacion";
            $vendedor = DB::select($queryusu);
            $usu = User::where("id", Auth::user()->id)->get();
            $pdf = \PDF::loadView('admin.reportes.pdfLiquidacion', compact('pedidos','abonos', 'usu', 'mes', 'vendedor'));
            return $pdf->download('ReporteLiquidaciónMensual.pdf');
  }

  public function reporteGanancias(Request $request){

    $fechaInicial = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
    $fechaFinal = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);
    $fechaInicialf = Carbon::createFromFormat('Y-m-d', $request->fechaInicial);
    $fechaFinalf = Carbon::createFromFormat('Y-m-d', $request->fechaFinal);

      $query = "SELECT pedidos_detalle.producto_id as id, productos.nombre_comercial as producto,
                SUM(pedidos_detalle.cantidad) as cantidad, pedidos_detalle.precio as precio,
                SUM(pedidos_detalle.subtotal) as subtotal
                FROM pedidos_detalle
                INNER JOIN productos ON pedidos_detalle.producto_id = productos.id
                INNER JOIN pedidos_maestro ON pedidos_maestro.id = pedidos_detalle.pedido_maestro_id
                WHERE pedidos_maestro.fecha_ingreso BETWEEN '$fechaInicial' AND '$fechaFinal'
                AND pedidos_detalle.estado = 1
                GROUP BY pedidos_detalle.producto_id, pedidos_detalle.precio,  productos.nombre_comercial
                ORDER BY id ASC  ";

      $ventas = DB::select($query);
      $query1 = "SELECT precio_compra as precio, producto_id as producto
                  FROM movimientos_producto
                  WHERE existencias > 0
                  GROUP BY producto_id, precio_compra
                ";
      $compras = DB::select($query1);
      $usu = User::where("id", Auth::user()->id)->get();
      $pdf = \PDF::loadView('admin.reportes.pdfGanancias', compact('compras','ventas', 'usu', 'fechaInicialf', 'fechaFinalf'));
      return $pdf->download('ReporteGanancias.pdf');

  }

  public function reporteAbonosClientes(Request $request){

    $inicial = Carbon::parse($request->fechaInicial)->format('Y-m-d');
    $final = Carbon::parse($request->fechaFinal)->format('Y-m-d');
    $fechaInicialf = Carbon::parse($request->fechaInicial)->format('Y-m-d');
    $fechaFinalf = Carbon::parse($request->fechaFinal)->format('Y-m-d');

    if ($request->vendedor == "todos"){
      $query = 'SELECT cuentas_cobrar_detalle_abono.fecha_ingreso as fecha,
                cuentas_cobrar_detalle_abono.monto as monto,
                cuentas_cobrar_detalle_abono.no_documento as documento,
                clientes.nombre_cliente as cliente,
                users.name as usuario
                FROM cuentas_cobrar_detalle_abono
                INNER JOIN cuentas_cobrar_maestro ON cuentas_cobrar_maestro.id = cuentas_cobrar_detalle_abono.cuentas_cobrar_maestro_id
                INNER JOIN clientes ON clientes.id = cuentas_cobrar_maestro.id_cliente
                INNER JOIN users on users.id = cuentas_cobrar_detalle_abono.user_id
                WHERE cuentas_cobrar_detalle_abono.fecha_ingreso BETWEEN "' . $inicial . '" AND "' . $final .'" AND cuentas_cobrar_detalle_abono.estado = 1';
                $abonos = DB::select($query);

    }else {

      $query = 'SELECT cuentas_cobrar_detalle_abono.fecha_ingreso as fecha,
                cuentas_cobrar_detalle_abono.monto as monto,
                cuentas_cobrar_detalle_abono.no_documento as documento,
                clientes.nombre_cliente as cliente,
                users.name as usuario
                FROM cuentas_cobrar_detalle_abono
                INNER JOIN cuentas_cobrar_maestro ON cuentas_cobrar_maestro.id = cuentas_cobrar_detalle_abono.cuentas_cobrar_maestro_id
                INNER JOIN clientes ON clientes.id = cuentas_cobrar_maestro.id_cliente
                INNER JOIN users on users.id = cuentas_cobrar_detalle_abono.user_id
                WHERE cuentas_cobrar_detalle_abono.fecha_ingreso BETWEEN "' . $inicial . '" AND "' . $final .'" AND cuentas_cobrar_detalle_abono.estado = 1 
                AND users.id = ' . $request->vendedor;
                $abonos = DB::select($query);
    }
      $usu = User::where("id", Auth::user()->id)->get();
      $usuario = User::where("id", $request->vendedor)->get();
      $contador = count($usuario);
      $pdf = \PDF::loadView('admin.reportes.pdfAbonos', compact('abonos','usu', 'usu', 'fechaInicialf', 'fechaFinalf','usuario', 'contador'));
      return $pdf->download('ReporteAbonosClientes.pdf');
  }

  public function getUsuariosAbonosClientes(){
    $query = "SELECT users.name as name, users.id
    FROM users
    INNER JOIN model_has_roles ON users.id = model_id
    INNER JOIN roles ON roles.id = model_has_roles.role_id
    WHERE (roles.id = 2 or roles.id = 3)
    and estado = 1";

    $usuario = DB::select($query);
    return Response::json($usuario);
  }

}
