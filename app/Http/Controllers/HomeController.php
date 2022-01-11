<?php

namespace App\Http\Controllers;

use App\CuentaCobrarDetalleAbono;
use App\Factura;
use App\IngresoMaestro;
use App\PedidoMaestro;
use Illuminate\Http\Request;
use App\User;
use App\Visita;
use App\Bodega;
use App\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if($user->hasRole('Administrador') || $user->hasRole('Super-Administrador')){
            $stock = [];
            $date = date_format(date_create(), 'Y-m-d');
            $compras  = number_format((float)IngresoMaestro::where('estado_ingreso', 1)->where('proveedor_id','<>', 0)->whereDate('fecha_compra', $date)->sum('total_ingreso'), 2, '.', '');
            $ventas   = number_format((float)PedidoMaestro::where('estado', 1)->where('cliente_id', '<>', 0)->whereDate('fecha_ingreso', $date)->sum('total'), 2, '.', '');
            $abonos   = number_format((float)CuentaCobrarDetalleAbono::where('estado', 1)->whereDate('fecha_ingreso', $date)->sum('monto'), 2, '.', '');
            $facturas = number_format((float)Factura::where('estado', 1)->whereDate('fecha_factura', $date)->sum('total'), 2, '.', '');
            $bodegas = Bodega::select('bodegas.id as id', 'bodegas.nombre as nombre', 'users.name as name')
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
                $warehouse_stock[$bodega->nombre] = $stock;
            }
            $query = "SELECT nombre_cliente as cliente, encargado_compras as persona,
            nacimiento_compras as fecha, telefono_compras as telefono FROM clientes
            WHERE DATE_FORMAT(nacimiento_compras, '%m%d') = DATE_FORMAT(CURDATE(),'%m%d')";
            $EC= DB::select($query);
            $query1 = "SELECT nombre_cliente as cliente, encargado_paga as persona,
            nacimiento_paga as fecha, telefono_paga as telefono FROM clientes
            WHERE DATE_FORMAT(nacimiento_paga, '%m%d') = DATE_FORMAT(CURDATE(),'%m%d')";
            $EP = DB::select($query1);
            $query2 ="SELECT proveedores.nombre_comercial as proveedor, proveedores.dias_credito, ingresos_maestro.fecha_compra,
                      DATE_SUB(DATE_ADD(ingresos_maestro.fecha_compra,INTERVAL proveedores.dias_credito DAY),INTERVAL 15 DAY) as fecha_15,
                      DATE_ADD(ingresos_maestro.fecha_compra,INTERVAL proveedores.dias_credito DAY) as fecha_maxima,
                      ingresos_maestro.total_ingreso as monto,
                      ingresos_maestro.num_factura as factura,
                      DATEDIFF(DATE_ADD(ingresos_maestro.fecha_compra,INTERVAL proveedores.dias_credito DAY), CURDATE()) as dias_restantes
                      FROM ingresos_maestro
                      INNER JOIN proveedores on ingresos_maestro.proveedor_id = proveedores.id
                      INNER JOIN cuentas_pagar_detalle_compra ON cuentas_pagar_detalle_compra.ingreso_id = ingresos_maestro.id
                      WHERE CURDATE() >= DATE_SUB(DATE_ADD(ingresos_maestro.fecha_compra,INTERVAL proveedores.dias_credito DAY),INTERVAL 15 DAY)
                      AND ingresos_maestro.estado_ingreso = 1 AND proveedores.id <> 0
                      AND cuentas_pagar_detalle_compra.pago_factura =1";
            $FPP = DB::select($query2);

            return view('admin.dashboard', compact('compras', 'ventas', 'abonos', 'facturas', 'stock', 'EC', 'EP', 'FPP', 'bodegas'));
        } elseif ($user->hasRole('Vendedor')){
            $stock = [];
            $date = date_format(date_create(), 'Y-m-d');
            $hoy = date('d');
            $visitas = Visita::where([['estado', 1],['user_id', $user->id]])->where('cliente_id', '<>', 0)->whereRaw('DAY(fecha) = ?', [$hoy])->count();
            $ventas   = number_format((float)PedidoMaestro::where([['estado', 1],['user_id', $user->id]])->where('cliente_id', '<>', 0)->whereDate('fecha_ingreso', $date)->sum('total'), 2, '.', '');
            $abonos   = number_format((float)CuentaCobrarDetalleAbono::where([['estado', 1],['user_id', $user->id]])->whereDate('fecha_ingreso', $date)->sum('monto'), 2, '.', '');
            $bodegas = Bodega::select('bodegas.id as id', 'bodegas.nombre as nombre', 'users.name as name')
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
                $warehouse_stock[$bodega->nombre] = $stock;
            }
            $query = "SELECT nombre_cliente as cliente, encargado_compras as persona,
            nacimiento_compras as fecha, telefono_compras as telefono FROM clientes
            WHERE DATE_FORMAT(nacimiento_compras, '%m%d') = DATE_FORMAT(CURDATE(),'%m%d')";
            $EC= DB::select($query);
            $query1 = "SELECT nombre_cliente as cliente, encargado_paga as persona,
            nacimiento_paga as fecha, telefono_paga as telefono FROM clientes
            WHERE DATE_FORMAT(nacimiento_paga, '%m%d') = DATE_FORMAT(CURDATE(),'%m%d')";
            $EP = DB::select($query1);


            return view('admin.dashboard', compact('visitas', 'ventas', 'abonos', 'stock', 'EC', 'EP'));

        }
    }

    public function getSalesData() {
        $month = date('m');
        $user = Auth::user();

        if($user->hasRole('Administrador') || $user->hasRole('Super-Administrador')){

            $data = PedidoMaestro::select(
                'fecha_ingreso as date',
                DB::raw('SUM(total) as amount')
            )->where(
              'cliente_id',
              '<>',
              0
              )->groupBy(
                'fecha_ingreso'
            )->whereRaw('MONTH(fecha_ingreso) = ?', [$month])->get();

        } elseif($user->hasRole('Vendedor')){

            $data = PedidoMaestro::select(
                'fecha_ingreso as date',
                DB::raw('SUM(total) as amount')
            )->where(
                'user_id',
                $user->id
            )->where(
              'cliente_id',
              '<>',
              0
              )->groupBy(
                'fecha_ingreso'
            )->whereRaw('MONTH(fecha_ingreso) = ?', [$month])->get();
        }

        return Response::json($data);
    }

    public function getPurchaseData() {
        $month = date('m');

        $data = IngresoMaestro::select(
            'fecha_compra as date',
            DB::raw('SUM(total_ingreso) as amount')
        )->where(
          'proveedor_id',
          '<>',
          0
          )->where(
            'estado_ingreso',
            '=',
            1)->groupBy(
            'fecha_compra'
        )->whereRaw('MONTH(fecha_compra) = ?', [$month])->get();

        return Response::json($data);
    }

    public function getFacturaData(){
        $month = date('m');

        $data = Factura::select(
            'fecha_factura as date',
            DB::raw('SUM(total) as amount')
            )->groupBy(
                'fecha_factura'
              )->whereRaw('MONTH(fecha_factura) = ?', [$month])
              ->where('estado', 1)->get();

              return Response::json($data);
    }

    public function getAbonoData(){
        $month = date('m');
        $user = Auth::user();

        if($user->hasRole('Administrador') || $user->hasRole('Super-Adnimistrador')){

            $data = CuentaCobrarDetalleAbono::select(
                'fecha_ingreso as date',
                DB::raw('SUM(monto) as amount')
            )->where(
                'estado',
                1
            )->groupBy(
                'fecha_ingreso'
            )->whereRaw(
                'MONTH(fecha_ingreso) = ?', [$month]
            )->get();

        }elseif($user->hasRole('Vendedor')){

            $data = CuentaCobrarDetalleAbono::select(
                'fecha_ingreso as date',
                DB::raw('SUM(monto) as amount')
            )->where([
                ['user_id', $user->id],
                ['estado', 1]
            ])->groupBy(
                'fecha_ingreso'
            )->whereRaw(
                'MONTH(fecha_ingreso) = ?', [$month]
            )->get();

        }

        return Response::json($data);
    }
}
