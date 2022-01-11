<?php

namespace App\Http\Controllers;

use App\Events\ActualizacionBitacora;
use App\PresentacionProducto;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ProductosController extends Controller
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
        return view ('admin.productos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $presentaciones = PresentacionProducto::all();
        return view('admin.productos.create', compact('presentaciones'));
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
        $producto = Producto::create($data);
        $producto->save();

        event(new ActualizacionBitacora($producto->id, Auth::user()->id, 'Creación', '', $producto, 'productos'));

        return redirect()->route('productos.index')->withFlash('El producto se ha registrado exitosamente.');
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
    public function edit(Producto $producto)
    {
        $presentaciones = PresentacionProducto::all();
        return view('admin.productos.edit', compact('producto', 'presentaciones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $this->validate($request,[
            'codigo'=>'required',
            'nombre_comercial'=>'required',
            'nombre_generico'=>'required',
            'concentracion'=>'required',
            'precio_venta'=>'required',
            'presentacion'=>'required',
            'stock_maximo'=>'required',
            'stock_minimo'=>'required',
        ]);

        $nuevos_datos = array(
            'codigo' => $request->codigo,
            'nombre_comercial' => $request->nombre_comercial,
            'nombre_generico' => $request->nombre_generico,
            'concentracion' => $request->concentracion,
            'precio_venta' => $request->precio_venta,
            'presentacion' => $request->presentacion,
            'stock_maximo' => $request->stock_maximo,
            'stock_minimo' => $request->stock_minimo,
        );

        $json = json_encode($nuevos_datos);

        event(new ActualizacionBitacora($producto->id, Auth::user()->id, 'Edición', $producto, $json, 'productos'));

        $producto->update($request->all());

        return redirect()->route('productos.index', $producto)->withFlash('El producto se ha actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto, Request $request)
    {
        $producto->estado = 2;
        $producto->save();

        event(new ActualizacionBitacora($producto->id, Auth::user()->id, 'Inactivación', '', '', 'productos'));

        return Response::json(['success'=>'Éxito']);
    }

    public function activar(Producto $producto, Request $request)
    {
        $producto->estado = 1;
        $producto->save();

        event(new ActualizacionBitacora($producto->id, Auth::user()->id, 'Activación', '', '', 'productos'));

        return Response::json(['success' => 'Éxito']);
    }

    public function getJson(Request $params)
    {
        $api_result['data'] = Producto::select(
            'productos.codigo',
            'productos.nombre_comercial',
            'productos.nombre_generico',
            'productos.concentracion',
            'productos.precio_venta',
            'productos.estado',
            'productos.id',
            'presentaciones_producto.presentacion',
            DB::raw('SUM(movimientos_producto.existencias) as existencias')
        )->join(
            'presentaciones_producto',
            'productos.presentacion',
            '=',
            'presentaciones_producto.id'
        )->leftJoin(
            'movimientos_producto',
            'movimientos_producto.producto_id',
            '=',
            'productos.id'
        )->groupBy(
            'productos.codigo',
            'productos.nombre_comercial',
            'productos.nombre_generico',
            'productos.concentracion',
            'productos.precio_venta',
            'productos.estado',
            'productos.id',
            'presentaciones_producto.presentacion'
        )->get();

        return Response::json($api_result);
    }

    public function codigoDisponible()
    {
        $dato = Input::get('codigo');
        $query = Producto::where('codigo', $dato)->get();
        $contador = count($query);
        if ($contador == 0) {
            return 'false';
        } else {
            return 'true';
        }
    }
}
