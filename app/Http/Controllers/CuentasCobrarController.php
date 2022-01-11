<?php

namespace App\Http\Controllers;

use App\CuentaCobrarDetalleAbono;
use App\CuentaCobrarMaestro;
use App\Events\ActualizacionBitacora;
use App\FormaPago;
use App\NotaEnvio;
use App\AbonosParciales;
use App\PedidoMaestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use function PHPSTORM_META\map;

class CuentasCobrarController extends Controller
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
        return view('admin.cuentas_cobrar.index');
    }

    /**
     * Show the form for creating a new resource.
     *
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
        // dd($request);
        $maestro = CuentaCobrarMaestro::find($request->cuentas_cobrar_maestro_id);
        $user = Auth::user();

        if($maestro->saldo > 0 && $request->monto <= $maestro->saldo){

            $date = date_format(date_create($request->fecha_ingreso), "Y/m/d");
            $ccda = CuentaCobrarDetalleAbono::create([
                'fecha_ingreso' => $date,
                'monto' => $request->monto,
                'cuentas_cobrar_maestro_id' => $request->cuentas_cobrar_maestro_id,
                'forma_pago' => $request->forma_pago,
                'user_id' => $user->id,
                'no_documento' => $request->no_documento
            ]);

            $nota = NotaEnvio::where('pedido_id', '=', $request->pedidoAbono)->get()->first();
              $ap = AbonosParciales::create([
                    'id_pedidos_maestro' => $request->pedidoAbono,
                    'id_abonos' => $ccda->id,
                    'id_notas' => $nota->id,
              ]);
              $ap->save();
            event(new ActualizacionBitacora($ccda->id, Auth::user()->id, 'Creación', '', $ccda, 'cuentas_cobrar_detalle_abono'));
            $maestro->decrement('saldo', $request->monto);
            return Response::json(['success' => 'éxito', 'saldo' => $maestro->saldo]);
        } else {
            return Response::json(['errors' => 'fail'], 500);
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
        $cuenta = CuentaCobrarMaestro::select(
            'cuentas_cobrar_maestro.id',
            'cuentas_cobrar_maestro.fecha_ingreso as fecha',
            'cuentas_cobrar_maestro.saldo',
            'clientes.nombre_cliente as cliente'
        )->join(
            'clientes',
            'cuentas_cobrar_maestro.id_cliente',
            '=',
            'clientes.id'
        )->where(
            'cuentas_cobrar_maestro.id',
            '=',
            $id
        )->where(
            'cuentas_cobrar_maestro.id_cliente',
            '<>',
            0
        )->get();

        $pedidos = CuentaCobrarMaestro::select(
            'ccdp.id as id_p',
            'ccdp.fecha_ingreso',
            'pedidos_maestro.no_pedido as no_transaccion',
            'pedidos_maestro.total as monto'
        )->join(
            'cuentas_cobrar_detalle_pedido as ccdp',
            'ccdp.cuentas_cobrar_maestro_id',
            '=',
            'cuentas_cobrar_maestro.id'
        )->join(
            'pedidos_maestro',
            'ccdp.pedido_id',
            '=',
            'pedidos_maestro.id'
        )->where([
            ['cuentas_cobrar_maestro.id', '=', $id],
            ['ccdp.estado', '=', 1]
        ])->get();



        $abonos = CuentaCobrarMaestro::select(
            'ccda.fecha_ingreso',
            'ccda.monto as monto',
            'ccda.no_documento as no_transaccion',
            'formas_pago.nombre as forma_pago',
            'ccda.id as id_a'
        )->join(
            'cuentas_cobrar_detalle_abono as ccda',
            'ccda.cuentas_cobrar_maestro_id',
            '=',
            'cuentas_cobrar_maestro.id'
        )->join(
            'formas_pago',
            'ccda.forma_pago',
            '=',
            'formas_pago.id'
        )->where([
            ['cuentas_cobrar_maestro.id', '=', $id],
            ['ccda.estado', '=', 1]
        ])->get();
        $pe = 0;
        $ab = 0;
        $total = 0;
        for ($i=0; $i < sizeOf($pedidos); $i++) {
          if ($pedidos[$i]['monto'] > 0) {
              $pe += $pedidos[$i]['monto'];
          }
        }
        for ($i=0; $i < sizeOf($abonos); $i++) {
          $ab += $abonos[$i]['monto'];
        }
          $total = $pe - $ab;
          $c = CuentaCobrarMaestro::findOrFail($id);
          $c->saldo = $total;
          $c->save();


        $pedi =  CuentaCobrarMaestro::select(
            'ccdp.id as id_p',
            'ccdp.fecha_ingreso',
            'pedidos_maestro.no_pedido as numero',
            'pedidos_maestro.total as monto',
            'pedidos_maestro.id as id',
            'notas_envio.no_nota_envio as nota'
        )->join(
            'cuentas_cobrar_detalle_pedido as ccdp',
            'ccdp.cuentas_cobrar_maestro_id',
            '=',
            'cuentas_cobrar_maestro.id'
        )->join(
            'pedidos_maestro',
            'ccdp.pedido_id',
            '=',
            'pedidos_maestro.id'
        )->join(
            'notas_envio',
            'notas_envio.pedido_id', '=', 'pedidos_maestro.id'
          )->where([
            ['cuentas_cobrar_maestro.id', '=', $id],
            ['ccdp.estado', '=', 1]
        ])->get();

    /*  $query = "SELECT SUM(cuentas_cobrar_detalle_abono.monto) as total, abonos_parciales.id_pedidos_maestro as id
                FROM abonos_parciales
                INNER JOIN cuentas_cobrar_detalle_abono ON cuentas_cobrar_detalle_abono.id = abonos_parciales.id_abonos
                where cuentas_cobrar_detalle_abono.estado = 1
                GROUP BY abonos_parciales.id_pedidos_maestro";*/
         $query = "SELECT SUM(cuentas_cobrar_detalle_abono.monto) as total, abonos_parciales.id_pedidos_maestro as id
         FROM abonos_parciales
         INNER JOIN cuentas_cobrar_detalle_abono ON cuentas_cobrar_detalle_abono.id = abonos_parciales.id_abonos
          INNER JOIN cuentas_cobrar_maestro ON cuentas_cobrar_maestro.id = cuentas_cobrar_detalle_abono.cuentas_cobrar_maestro_id
          where cuentas_cobrar_detalle_abono.estado = 1 and cuentas_cobrar_maestro.id = $id
           GROUP BY abonos_parciales.id_pedidos_maestro";

      $ab = DB::select($query);
      if (empty( $ab )) {
        $ab = null;
      }



        return view('admin.cuentas_cobrar.show', compact('cuenta', 'pedidos', 'abonos', 'pedi', 'ab'));
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

      $user = Auth::user();
      $query = "SELECT roles.id as id
    FROM users
      INNER JOIN model_has_roles ON users.id = model_id
      INNER JOIN roles ON roles.id = model_has_roles.role_id
      WHERE users.id = $user->id";

      $rol= DB::select($query);

      $query1 = "SELECT territorios.id as id
      FROM territorios
      INNER JOIN users on users.id = territorios.user
      where users.id = $user->id";

      $territorio= DB::select($query1);
      $c = [];
      $c['data'] = 0;

      if ($rol[0]->id <= 2) {
        $api_result['data'] = CuentaCobrarMaestro::select(
            'cuentas_cobrar_maestro.id',
            'cuentas_cobrar_maestro.fecha_ingreso as fecha',
            'cuentas_cobrar_maestro.saldo',
            'clientes.nombre_cliente as cliente'
        )->join(
            'clientes',
            'cuentas_cobrar_maestro.id_cliente',
            '=',
            'clientes.id'
        )->where(
            'cuentas_cobrar_maestro.id_cliente',
            '<>',
            0
        )->get();

        return Response::json($api_result);
      }else if ($rol[0]->id == 3) {

        $o = 0;
        $n = sizeof($territorio);
        $n = $n;

        if (!empty( $territorio )) {
        
        $territorios2 = "";

        for ($i=0; $i < $n; $i++) {
          $t = $territorio[$o]->id;

          if($i == 0) {
            $territorios2 = $territorios2 . $t;
          }else{
            $territorios2 = $territorios2 . ', ' . $t;
          }        

          $o++;
        }

        $query33 = "SELECT ccm.id, ccm.fecha_ingreso as fecha, ccm.saldo, cl.nombre_cliente as cliente
        FROM cuentas_cobrar_maestro ccm
        INNER JOIN clientes cl ON ccm.id_cliente = cl.id
        WHERE ccm.id_cliente > 0 AND cl.territorio IN (" . $territorios2 .") ";
        $api_result = DB::select($query33);  

         $c['data'] = $api_result;
        
      }

      return Response::json($c);
    }

    }

    public function getDetalles($id){
        $pedidos = CuentaCobrarMaestro::select(
            'ccdp.id as id_p',
            'ccdp.fecha_ingreso',
            'notas_envio.no_nota_envio as no_transaccion',
            'pedidos_maestro.total as monto'
        )->join(
            'cuentas_cobrar_detalle_pedido as ccdp',
            'ccdp.cuentas_cobrar_maestro_id',
            '=',
            'cuentas_cobrar_maestro.id'
        )->join(
            'pedidos_maestro',
            'ccdp.pedido_id',
            '=',
            'pedidos_maestro.id'
        )->join(    
            'notas_envio',
            'pedidos_maestro.id',
            '=',
            'notas_envio.pedido_id'
        )->where([
            ['cuentas_cobrar_maestro.id', '=', $id],
            ['ccdp.estado', '=', 1]
        ])->get()->toArray();
        for ($i=0; $i < sizeOf($pedidos); $i++) {
            $pedidos[$i]['tipo'] = 'Pedido';
        }
        //anterior
      /*  $abonos = CuentaCobrarMaestro::select(
            'ccda.fecha_ingreso',
            'ccda.monto as monto',
            'ccda.no_documento as no_transaccion',
            'formas_pago.nombre as forma_pago',
            'ccda.id as id_a'
        )->join(
            'cuentas_cobrar_detalle_abono as ccda',
            'ccda.cuentas_cobrar_maestro_id',
            '=',
            'cuentas_cobrar_maestro.id'
        )->join(
            'formas_pago',
            'ccda.forma_pago',
            '=',
            'formas_pago.id'
        )->where([
            ['cuentas_cobrar_maestro.id', '=', $id],
            ['ccda.estado', '=', 1]
        ])->get()->toArray();
        for ($i=0; $i < sizeOf($abonos); $i++) {
            $abonos[$i]['tipo'] = 'Abono';
        }*/

        $abonos1 = CuentaCobrarMaestro::select(
            'ccd.fecha_ingreso',
            'ccd.monto as monto',
            'notas_envio.no_nota_envio as no_transaccion',
            'formas_pago.nombre as forma_pago',
            'ccd.id as id_a'
          )->join(
              'cuentas_cobrar_detalle_abono as ccd',
              'ccd.cuentas_cobrar_maestro_id',
              '=',
              'cuentas_cobrar_maestro.id'
          )->join(
              'formas_pago',
              'ccd.forma_pago',
              '=',
              'formas_pago.id'
          )->Leftjoin(
              'abonos_parciales',
              'ccd.id',
              '=',
              'abonos_parciales.id_abonos'
          )->Leftjoin(
              'pedidos_maestro',
              'pedidos_maestro.id',
              '=',
              'abonos_parciales.id_pedidos_maestro'
        )->join(    
              'notas_envio',
              'pedidos_maestro.id',
              '=',
              'notas_envio.pedido_id'
          )->where([
              ['cuentas_cobrar_maestro.id', '=', $id],
              ['ccd.estado', '=', 1]
          ])->get()->toArray();
          for ($i=0; $i < sizeOf($abonos1); $i++) {
              $abonos1[$i]['tipo'] = 'Abono';
          }


        $api_result['data'] = array_merge($pedidos, $abonos1);
        return Response::json($api_result);

    }

    public function destroyAbono($id){
        $abono = CuentaCobrarDetalleAbono::find($id);
        $maestro = CuentaCobrarMaestro::find($abono->cuentas_cobrar_maestro_id);

        $maestro->increment('saldo', $abono->monto);
        $abono->update(['estado' => 2]);
        event(new ActualizacionBitacora($abono->id, Auth::user()->id, 'Eliminación', '', '', 'cuentas_cobrar_detalle_abono'));

        return Response::json(['success' => 'éxito', 'saldo' => $maestro->saldo]);
    }

    public function saldo($id){


    $query = "SELECT SUM(pedidos_maestro.total) - SUM(cuentas_cobrar_detalle_abono.monto)   as saldo
              FROM abonos_parciales
              INNER JOIN cuentas_cobrar_detalle_abono ON cuentas_cobrar_detalle_abono.id = abonos_parciales.id_abonos
              INNER JOIN pedidos_maestro ON pedidos_maestro.id = abonos_parciales.id_pedidos_maestro
              where cuentas_cobrar_detalle_abono.estado = 1 AND pedidos_maestro.id = $id
              GROUP BY abonos_parciales.id_pedidos_maestro, pedidos_maestro.id, cuentas_cobrar_detalle_abono.id";
    $ab = DB::select($query);
      if (empty( $ab )) {
      $query1 = "select total as saldo
                from pedidos_maestro
                where id = $id";
        $ab = DB::select($query1);
      }

      return Response::json($ab);
    }


}
