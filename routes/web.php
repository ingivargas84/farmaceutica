<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\FacturasController;

Route::group([
    'middleware'=>['auth','estado'] ],
function(){
    Route::get('/admin','HomeController@index')->name('dashboard');
    Route::get('/admin/salesData','HomeController@getSalesData')->name('dashboard.salesData');
    Route::get('/admin/purchaseData','HomeController@getPurchaseData')->name('dashboard.purchaseData');
    Route::get('/admin/facturaData','HomeController@getFacturaData')->name('dashboard.facturaData');
    Route::get('/admin/abonoData','HomeController@getAbonoData')->name('dashboard.abonoData');

    Route::get('user/getJson' , 'UsersController@getJson' )->name('users.getJson');
    Route::get('users' , 'UsersController@index' )->name('users.index');
    Route::post('users' , 'UsersController@store' )->name('users.store');
    Route::delete('users/{user}' , 'UsersController@destroy' );
    Route::post('users/update/{user}' , 'UsersController@update' );
    Route::get('users/{user}/edit', 'UsersController@edit' );
    Route::post('users/reset/tercero' , 'UsersController@resetPasswordTercero')->name('users.reset.tercero');
    Route::post('users/reset' , 'UsersController@resetPassword')->name('users.reset');
    Route::get( '/users/cargar' , 'UsersController@cargarSelect')->name('users.cargar');
    Route::get( '/users/cargarA' , 'UsersController@cargarSelectApertura')->name('users.cargarA');

    Route::get( '/negocio/{negocio}/edit' , 'NegocioController@edit')->name('negocio.edit');
    Route::put( '/negocio/{negocio}/update' , 'NegocioController@update')->name('negocio.update');

    Route::get( '/proveedores' , 'ProveedoresController@index')->name('proveedores.index');
    Route::get( '/proveedores/getJson/' , 'ProveedoresController@getJson')->name('proveedores.getJson');
    Route::get( '/proveedores/new' , 'ProveedoresController@create')->name('proveedores.new');
    Route::get( '/proveedores/edit/{proveedor}' , 'ProveedoresController@edit')->name('proveedores.edit');
    Route::put( '/proveedores/{proveedor}/update' , 'ProveedoresController@update')->name('proveedores.update');
    Route::post( '/proveedores/save/' , 'ProveedoresController@store')->name('proveedores.save');
    Route::post('/proveedores/{proveedor}/delete' , 'ProveedoresController@destroy');
    Route::post('/proveedores/{proveedor}/activar' , 'ProveedoresController@activar');
    Route::get('/proveedores/nitDisponible/', 'ProveedoresController@nitDisponible')->name('proveedores.nitDisponible');

    Route::get( '/territorios' , 'TerritoriosController@index')->name('territorios.index');
    Route::get( '/territorios/getJson/' , 'TerritoriosController@getJson')->name('territorios.getJson');
    Route::get( '/territorios/new' , 'TerritoriosController@create')->name('territorios.new');
    Route::get( '/territorios/edit/{territorio}' , 'TerritoriosController@edit')->name('territorios.edit');
    Route::put( '/territorios/{territorio}/update' , 'TerritoriosController@update')->name('territorios.update');
    Route::post( '/territorios/save/' , 'TerritoriosController@store')->name('territorios.save');
    Route::post('/territorios/{territorio}/delete' , 'TerritoriosController@destroy');
    Route::post('/territorios/{territorio}/activar' , 'TerritoriosController@activar');
    Route::get('/territorios/territorioDisponible' , 'TerritoriosController@territorioDisponible')->name('territorios.territorioDisponible');
    Route::get('/territorios/territorioDisponibleEditar' , 'TerritoriosController@territorioDisponibleEditar')->name('territorios.territorioDisponibleEditar');
    Route::get( '/territorios/new1' , 'TerritoriosController@create1')->name('territorios.new1');

    Route::get( '/visitas' , 'VisitaController@index')->name('visitas.index');
    Route::get( '/visitas/getJson/' , 'VisitaController@getJson')->name('visitas.getJson');
    Route::get( '/visitas/new' , 'VisitaController@create')->name('visitas.new');
    Route::get( '/visitas/new2' , 'VisitaController@create2')->name('visitas.new2');
    Route::post( '/visitas/save/' , 'VisitaController@store')->name('visitas.save');
    Route::post( '/visitas/save2/' , 'VisitaController@store2')->name('visitas.save2');
    Route::get( '/visitas/edit/{visita}' , 'VisitaController@edit')->name('visitas.edit');
    Route::put( '/visitas/{visita}/update' , 'VisitaController@update')->name('visitas.update');
    Route::post('/visitas/{visita}/delete' , 'VisitaController@destroy');

    Route::get( '/clientes' , 'ClientesController@index')->name('clientes.index');
    Route::get( '/clientes/getJson/' , 'ClientesController@getJson')->name('clientes.getJson');
    Route::get( '/clientes/new' , 'ClientesController@create')->name('clientes.new');
    Route::get( '/clientes/edit/{cliente}' , 'ClientesController@edit')->name('clientes.edit');
    Route::put( '/clientes/{cliente}/update' , 'ClientesController@update')->name('clientes.update');
    Route::post( '/clientes/save/' , 'ClientesController@store')->name('clientes.save');
    Route::post('/clientes/{cliente}/delete' , 'ClientesController@destroy');
    Route::post('/clientes/{cliente}/activar' , 'ClientesController@activar');
    Route::get('/clientes/nitDisponible/', 'ClientesController@nitDisponible')->name('clientes.nitDisponible');

    Route::get( '/bodegas' , 'BodegasController@index')->name('bodegas.index');
    Route::get( '/bodegas/getJson/' , 'BodegasController@getJson')->name('bodegas.getJson');
    Route::get( '/bodegas/new' , 'BodegasController@create')->name('bodegas.new');
    Route::get( '/bodegas/new1' , 'BodegasController@create1')->name('bodegas.new1');
    Route::get( '/bodegas/edit/{bodega}' , 'BodegasController@edit')->name('bodegas.edit');
    Route::get( '/bodegas/edit1/{bodega}' , 'BodegasController@edit1')->name('bodegas.edit1');
    Route::put( '/bodegas/{bodega}/update' , 'BodegasController@update')->name('bodegas.update');
    Route::post( '/bodegas/save/' , 'BodegasController@store')->name('bodegas.save');
    Route::post('/bodegas/{bodega}/delete' , 'BodegasController@destroy');
    Route::get('/bodegas/{bodega}/activar' , 'BodegasController@activar');

    Route::get( '/formas_pago' , 'FormasPagoController@index')->name('formas_pago.index');
    Route::get( '/formas_pago/getJson/' , 'FormasPagoController@getJson')->name('formas_pago.getJson');
    Route::get( '/formas_pago/new' , 'FormasPagoController@create')->name('formas_pago.new');
    Route::get( '/formas_pago/edit/{formaPago}' , 'FormasPagoController@edit')->name('formas_pago.edit');
    Route::put( '/formas_pago/{formaPago}/update' , 'FormasPagoController@update')->name('formas_pago.update');
    Route::post( '/formas_pago/save/' , 'FormasPagoController@store')->name('formas_pago.save');
    Route::post('/formas_pago/{formaPago}/delete' , 'FormasPagoController@destroy');
    // Route::post('/formas_pago/{formaPago}/activar' , 'FormasPagoController@activar');
    Route::get('/formas_pago/nombreDisponible/', 'FormasPagoController@nombreDisponible')->name('formas_pago.nombreDisponible');

    Route::get( '/presentaciones_producto' , 'PresentacionesProductoController@index')->name('presentaciones_producto.index');
    Route::get( '/presentaciones_producto/getJson/' , 'PresentacionesProductoController@getJson')->name('presentaciones_producto.getJson');
    Route::get( '/presentaciones_producto/new' , 'PresentacionesProductoController@create')->name('presentaciones_producto.new');
    Route::get( '/presentaciones_producto/edit/{presentacionProducto}' , 'PresentacionesProductoController@edit')->name('presentaciones_producto.edit');
    Route::put( '/presentaciones_producto/{presentacionProducto}/update' , 'PresentacionesProductoController@update')->name('presentaciones_producto.update');
    Route::post( '/presentaciones_producto/save/' , 'PresentacionesProductoController@store')->name('presentaciones_producto.save');
    Route::post('/presentaciones_producto/{presentacionProducto}/delete' , 'PresentacionesProductoController@destroy');
    // Route::post('/presentaciones_producto/{presentacionProducto}/activar' , 'PresentacionesProductoController@activar');
    Route::get('/presentaciones_producto/nombreDisponible/', 'PresentacionesProductoController@nombreDisponible')->name('presentaciones_producto.nombreDisponible');

    Route::get( '/productos' , 'ProductosController@index')->name('productos.index');
    Route::get( '/productos/getJson/' , 'ProductosController@getJson')->name('productos.getJson');
    Route::get( '/productos/new' , 'ProductosController@create')->name('productos.new');
    Route::get( '/productos/edit/{producto}' , 'ProductosController@edit')->name('productos.edit');
    Route::put( '/productos/{producto}/update' , 'ProductosController@update')->name('productos.update');
    Route::post( '/productos/save/' , 'ProductosController@store')->name('productos.save');
    Route::post('/productos/{producto}/delete' , 'ProductosController@destroy');
    Route::post('/productos/{producto}/activar' , 'ProductosController@activar');
    Route::get('/productos/codigoDisponible' , 'ProductosController@codigoDisponible')->name('productos.codigoDisponible');

    Route::get('/compras/getProductoData/{producto}' , 'ComprasController@getProductoData')->name('compras.getProductoData');
    Route::get('/compras/getProductoDataNombre/{producto}' , 'ComprasController@getProductoDataNombre')->name('compras.getProductoData');
    Route::get('/compras' , 'ComprasController@index')->name('compras.index');
    Route::get('/compras/getJson/' , 'ComprasController@getJson')->name('compras.getJson');
    Route::get('/compras/new' , 'ComprasController@create')->name('compras.new');
    Route::post( '/compras/save/' , 'ComprasController@store')->name('compras.save');
    Route::get('/compras/{ingresoMaestro}' , 'ComprasController@show')->name('compras.show');
    Route::get('/compras/edit/{compra}' , 'ComprasController@edit')->name('compras.edit');
    Route::put('/compras/{compra}/update' , 'ComprasController@update')->name('compras.update');
    Route::post('/compras/{ingresoMaestro}/delete' , 'ComprasController@destroy');
    Route::post('/compras/{ingresoDetalle}/deleteDetalle' , 'ComprasController@destroyDetalle');
    Route::get('/compras/{ingresoMaestro}/getDetalles' , 'ComprasController@getDetalles')->name('compras.getDetalles');

    Route::get('/traspasos_bodega/getJson/' , 'TraspasosBodegaController@getJson')->name('traspasos_bodega.getJson');
    Route::get('/traspasos_bodega/getProducto/{producto}/{bodega}' , 'TraspasosBodegaController@getProduct')->name('traspasos_bodega.getProducto');
    Route::get('/traspasos_bodega/getProductoNombre/{producto}/{bodega}' , 'TraspasosBodegaController@getProductName')->name('traspasos_bodega.getProducto');
    Route::get('/traspasos_bodega' , 'TraspasosBodegaController@index')->name('traspasos_bodega.index');
    Route::get('/traspasos_bodega/new' , 'TraspasosBodegaController@create')->name('traspasos_bodega.new');
    Route::post( '/traspasos_bodega/save/' , 'TraspasosBodegaController@store')->name('traspasos_bodega.save');
    Route::get('/traspasos_bodega/{traspasoBodega}' , 'TraspasosBodegaController@show')->name('traspasos_bodega.show');
    Route::get('/traspasos_bodega/edit/{compra}' , 'TraspasosBodegaController@edit')->name('traspasos_bodega.edit');
    Route::put('/traspasos_bodega/{compra}/update' , 'TraspasosBodegaController@update')->name('traspasos_bodega.update');
    Route::post('/traspasos_bodega/{traspasoBodega}/delete' , 'TraspasosBodegaController@destroy');
    Route::post('/traspasos_bodega/{ingresoDetalle}/deleteDetalle' , 'TraspasosBodegaController@destroyDetalle');
    Route::get('/traspasos_bodega/{traspasoBodega}/getDetalles' , 'TraspasosBodegaController@getDetalles')->name('traspasos_bodega.getDetalles');

    Route::get('/pedidos/getJson/' , 'PedidosController@getJson')->name('pedidos.getJson');
    Route::get('/pedidos/getProductoData/{codigo}/{bodega}' , 'PedidosController@getProductoData')->name('pedidos.getProductoData');
    Route::get('/pedidos/getProductoData1/{codigo}/{bodega}' , 'PedidosController@getProductoData1');
    Route::get('/pedidos/getFactura/{factura}' , 'PedidosController@getFactura')->name('pedidos.getFactura');
    Route::get('/pedidos' , 'PedidosController@index')->name('pedidos.index');
    Route::get('/pedidos/new/{bodega}' , 'PedidosController@create')->name('pedidos.new');
    Route::post('/pedidos/save/' , 'PedidosController@store')->name('pedidos.save');
    Route::post('/pedidos/save1/' , 'PedidosController@store1')->name('pedidos.save1');
    Route::get('/pedidos/{pedidoMaestro}' , 'PedidosController@show')->name('pedidos.show');
    Route::post('/pedidos/{pedidoMaestro}/delete' , 'PedidosController@destroy');
    Route::post('/pedidos/{pedidoDetalle}/deleteDetalle' , 'PedidosController@destroyDetalle');
    Route::get('/pedidos/{pedidoMaestro}/getDetalles' , 'PedidosController@getDetalles')->name('pedidos.getDetalles');
    Route::get('/pedidos/{pedidoMaestro}/getDetalles1' , 'PedidosController@getDetalles1')->name('pedidos.getDetalles1');
    Route::get('/pedidos/editarDetalle/{id}' , 'PedidosController@editarDetalle')->name('pedidos.editarDetalle');
    Route::put('/pedidos/actulizarDetalle/{id}' , 'PedidosController@actulizarDetalle')->name('pedidos.actulizarDetalle');

    Route::get('/cuentas_pagar/getJson/' , 'CuentasPagarController@getJson')->name('cuentas_pagar.getJson');
    Route::get('/cuentas_pagar' , 'CuentasPagarController@index')->name('cuentas_pagar.index');
    Route::get('/cuentas_pagar/new' , 'CuentasPagarController@create')->name('cuentas_pagar.new');
    Route::post('/cuentas_pagar/save/' , 'CuentasPagarController@store')->name('cuentas_pagar.save');
    Route::get('/cuentas_pagar/{cuentaPagarMaestro}' , 'CuentasPagarController@show')->name('cuentas_pagar.show');
    Route::post('/cuentas_pagar/{pedidoDetalle}/deleteAbono' , 'CuentasPagarController@destroyAbono');
    Route::get('/cuentas_pagar/{cuentaPagarMestro}/getDetalles' , 'CuentasPagarController@getDetalles')->name('cuentas_pagar.getDetalles');
    Route::get('/cuentas_pagar/monto/{id}' , 'CuentasPagarController@monto')->name('cuentas_pagar.monto');
    Route::get('/cuentas_pagar/facturas/{id}' , 'CuentasPagarController@facturas')->name('cuentas_pagar.facturas');

    Route::get('/cuentas_cobrar/getJson/' , 'CuentasCobrarController@getJson')->name('cuentas_cobrar.getJson');
    Route::get('/cuentas_cobrar' , 'CuentasCobrarController@index')->name('cuentas_cobrar.index');
    Route::get('/cuentas_cobrar/new' , 'CuentasCobrarController@create')->name('cuentas_cobrar.new');
    Route::post('/cuentas_cobrar/save/' , 'CuentasCobrarController@store')->name('cuentas_cobrar.save');
    Route::get('/cuentas_cobrar/{cuentaPagarMaestro}' , 'CuentasCobrarController@show')->name('cuentas_cobrar.show');
    Route::post('/cuentas_cobrar/{id}/deleteAbono' , 'CuentasCobrarController@destroyAbono');
    Route::get('/cuentas_cobrar/{cuentaPagarMestro}/getDetalles' , 'CuentasCobrarController@getDetalles')->name('cuentas_cobrar.getDetalles');
    Route::get('/cuentas_cobrar/abonosParciales/{id}' , 'CuentasCobrarController@saldo')->name('cuentas_cobrar.saldo');

    Route::get('/notas_envio/getJson/' , 'NotasEnvioController@getJson')->name('notas_envio.getJson');
    Route::get('/notas_envio/getPedidoData/{pedidoMaestro}' , 'NotasEnvioController@getPedidoData')->name('notas_envio.getPedidoData');
    Route::get('/notas_envio/getFactura/{factura}' , 'NotasEnvioController@getFactura')->name('notas_envio.getFactura');
    Route::get('/notas_envio' , 'NotasEnvioController@index')->name('notas_envio.index');
    Route::get('/notas_envio/new' , 'NotasEnvioController@create')->name('notas_envio.new');
    Route::post('/notas_envio/save/' , 'NotasEnvioController@store')->name('notas_envio.save');
    Route::get('/notas_envio/edit/{notaEnvio}' , 'NotasEnvioController@edit');
    Route::put('/notas_envio/{notaEnvio}/update', 'NotasEnvioController@update');
    Route::post('/notas_envio/{notaEnvio}/delete' , 'NotasEnvioController@destroy');
    Route::post('/notas_envio/{pedidoDetalle}/deleteAbono' , 'NotasEnvioController@destroyAbono');
    Route::get('/notas_envio/{cuentaPagarMestro}/getDetalles' , 'NotasEnvioController@getDetalles')->name('notas_envio.getDetalles');

    Route::get('/facturas', 'FacturasController@index')->name('facturas.index');
    Route::get('/facturas/getJson', 'FacturasController@getJson')->name('facturas.getJson');
    Route::get('/facturas/{id}', 'FacturasController@show')->name('facturas.show');
    Route::post('/facturas/{id}/delete', 'FacturasController@destroy')->name('facturas.delete');
    Route::get('/facturas/pedidos/getPedidos' , 'FacturasController@getPedidos')->name('facturas.pedidos');
    Route::get('/facturas/clientes/{id}' , 'FacturasController@InformacionCliente')->name('facturas.informacionCliente');
    Route::post('/facturas/save/' , 'FacturasController@store')->name('facturas.save');
    Route::get('/factura/noFacturaDisponible/', 'FacturasController@noFacturaDisponible')->name('facturas.noFacturaDisponible');
    Route::get('/factura1/noSerieDisponible/', 'FacturasController@noSerieDisponible')->name('facturas.noSerieDisponible');
    Route::get('/facturas/NuevaFactura/{id}', 'FacturasController@NuevaFactura')->name('facturas.NuevaFactura');

    Route::get('/partidas_ajuste', 'PartidasAjusteController@index')->name('partidas_ajuste.index');
    Route::get('/partidas_ajuste/getJson', 'PartidasAjusteController@getJson')->name('partidas_ajuste.getJson');
    Route::get('/partidas_ajuste/{id}', 'PartidasAjusteController@show');
    Route::get('/partidas_ajuste/{id}/getDetalles', 'PartidasAjusteController@getDetalles');
    Route::get('/partidas_ajuste/new/{id}', 'PartidasAjusteController@create');
    Route::post('/partidas_ajuste/save', 'PartidasAjusteController@store')->name('partidas_ajuste.save');

    Route::get('/reportes/minmax' , 'ReportesController@minMaxJson')->name('reportes.minmax');
    Route::get('/reportes/stock' , 'ReportesController@stockJson')->name('reportes.stock');
    Route::get('/reportes/warehouseStock' , 'ReportesController@warehouseStockJson')->name('reportes.warehouse_stock');
    Route::get('/reportes/warehouseStock1' , 'ReportesController@warehouseStockVendedorJson')->name('reportes.warehouse_stock_vendedor');
    Route::get('/reportes/expiration' , 'ReportesController@expirationJson')->name('reportes.expiration');
    Route::get('/reportes/client_balance/{date}' , 'ReportesController@clientBalanceJson')->name('reportes.client_balance');
    Route::post('/reportes/visitas/' , 'ReportesController@pdfVisitas')->name('reportes.visitas');
    Route::get('/reportes/usuarios', 'ReportesController@getUsuarios')->name('reportes.usuarios');
    Route::post('/reportes/ventas', 'ReportesController@pdfVentasVendedor')->name('reportes.ventas');
    Route::post('/reportes/proveedores', 'ReportesController@pdfSaldoProveedores')->name('reportes.proveedores');
    Route::get('/reportes/compras', 'ReportesController@getProveedores');
    Route::get('/reportes/territorios', 'ReportesController@getTerritorios');
    Route::post('/reportes/comprasProveedores', 'ReportesController@reporteComprasProveedores')->name('reportes.comprasP');
    Route::post('/reportes/saldosTerritorios', 'ReportesController@reporteSaldosTerritorios')->name('reportes.saldosT');
    Route::post('/reportes/traspasoBodega', 'ReportesController@pdfTraspasoBodegas')->name('reportes.traspasoBodega');
    Route::post('/reportes/reporteMes', 'ReportesController@reporteMes')->name('reportes.reporteMes');
    Route::post('/reportes/liquidacionMensual', 'ReportesController@liquidacionMensual')->name('reportes.liquidacionMensual');
    Route::post('/reportes/reporteGanancias', 'ReportesController@reporteGanancias')->name('reportes.reporteGanancias');
    Route::post('/reportes/reporteAbonosClientes', 'ReportesController@reporteAbonosClientes')->name('reportes.reporteAbonosClientes');
    Route::get('/reportes/usuariosAbonos', 'ReportesController@getUsuariosAbonosClientes')->name('reportes.usuariosAbonos');


    //devoluciones
    Route::get('/devoluciones/pedidos', 'PedidosController@getPedidos');
    Route::post('/devoluciones' , 'PedidosController@devolucion')->name('devoluciones.index');
    Route::get('/devoluciones/devolucion/{id}' , 'PedidosController@devoluciones')->name('devolcuiones.getDetalles');
    Route::post('/devoluciones/save/' , 'PedidosController@storeDevoluciones')->name('pedidos.devoluciones');
});


Route::get('/', function () {
    $negocio = App\Negocio::all();
    return view('welcome', compact('negocio'));
});

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home')->middleware(['estado']);

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/user/get/' , 'Auth\LoginController@getInfo')->name('user.get');
Route::post('/user/contador' , 'Auth\LoginController@Contador')->name('user.contador');
Route::post('/password/reset2' , 'Auth\ForgotPasswordController@ResetPassword')->name('password.reset2');
Route::get('/user-existe/', 'Auth\LoginController@userExiste')->name('user.existe');

//Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
/*Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');*/
