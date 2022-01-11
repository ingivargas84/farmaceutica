<?php

namespace App\Http\Controllers;

use App\Events\ActualizacionBitacora;
use App\NotaEnvio;
use App\PedidoMaestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class NotasEnvioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.notas_envio.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get all pedidoMaestro without a notaEnvio
        $pedidos = PedidoMaestro::select(
            'pedidos_maestro.id',
            'pedidos_maestro.fecha_ingreso',
            'pedidos_maestro.no_pedido',
            'clientes.nombre_cliente',
            'ne.no_nota_envio as no_nota'
        )->join(
            'clientes',
            'pedidos_maestro.cliente_id',
            'clientes.id'
        )->leftJoin(
            //explanation for this query in PedidosController@getJson
            DB::raw(
                '(select * from notas_envio where notas_envio.estado = 1) ne'
            ),
            function ($join) {
                $join->on(
                    'ne.pedido_id',
                    '=',
                    'pedidos_maestro.id'
                );
            }
        )->where([
            /*
            this 'where' cheks for rows that are active
            and have a soft deleted 'nota_envio' clild
            */
            ['ne.estado', '!=', 1],
            ['pedidos_maestro.estado', '=', 1]
        ])->orWhere([
            /*
            this 'where' cheks for rows that are active
            but don't have a 'nota_envio' clild
            */
            ['ne.estado', '=', null],
            ['pedidos_maestro.estado', '=', 1]
        ])->where(
            'clientes.id',
            '<>',
            0
        )->get();

        return view('admin.notas_envio.create', compact('pedidos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ne = NotaEnvio::create([
            'cliente'   => $request->cliente,
            'direccion' => $request->direccion,
            'telefono'  => $request->telefono,
            'pedido_id' => $request->pedidos
        ]);

        event(new ActualizacionBitacora($ne->id, Auth::user()->id, 'Creación', '', $ne, 'notas_envio'));

        return Response::json(['success' => 'éxito'], 200);
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
        $ne = NotaEnvio::find($id);
        return Response::json($ne);
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
        $ne = NotaEnvio::find($id);
        $ne->update([
            'cliente' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' =>$request->telefono
        ]);
        $nuevo = array(
            'cliente' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' =>$request->telefono
        );
        $json = json_encode($nuevo);

        event(new ActualizacionBitacora($ne->id, Auth::user()->id, 'Edición', $ne, $json, 'notas_envio'));

        return Response::json(['success' => 'éxito'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ne = NotaEnvio::find($id);
        $ne->update(['estado' => 2]);
        event(new ActualizacionBitacora($ne->id, Auth::user()->id, 'Inactivación', '', '', 'notas_envio'));

        return Response::json(['success' => 'éxito'], 200);
    }

    public function getJson(){
        $api_result['data'] = NotaEnvio::select(
            'notas_envio.id',
            'notas_envio.no_nota_envio as no_nota',
            'notas_envio.cliente',
            'notas_envio.created_at',
            'notas_envio.telefono',
            'notas_envio.direccion',
            'estados_nota_envio.estado as estado_nota',
            'pedidos_maestro.no_pedido',
            'pedidos_maestro.total',
            'pedidos_maestro.id as id_m'
        )->join(
            'estados_nota_envio',
            'notas_envio.estado',
            '=',
            'estados_nota_envio.id'
        )->join(
            'pedidos_maestro',
            'notas_envio.pedido_id',
            '=',
            'pedidos_maestro.id'
        )->where(
            'notas_envio.estado',
            '=',
            1
        )->get();

        return Response::json($api_result);
    }

    public function getPedidoData($id){
        $api_result = PedidoMaestro::join(
            'clientes',
            'pedidos_maestro.cliente_id',
            'clientes.id'
        )->select(
            'pedidos_maestro.total',
            'clientes.nombre_cliente',
            'clientes.direccion',
            'clientes.telefono_compras'
        )->where(
            'pedidos_maestro.id',
            '=',
            $id
        )->get();

        return Response::json($api_result);
    }
}
