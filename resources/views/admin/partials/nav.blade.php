    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
        <li class="header">Navegacion</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="{{request()->is('admin')? 'active': ''}}">
            <a href="{{route('dashboard')}}">
                <i class="fa fa-home"></i>
                <span>Inicio</span>
            </a>
        </li>


        @role('Super-Administrador|Administrador|Vendedor')
        <li class="treeview {{request()->is('empleados*', 'puestos*','destinos_pedidos*','tipos_localidad*','localidades*','unidades_medida*','categorias_insumos*','insumos*', 'productos*', 'categorias_menus*', 'recetas*', 'cajas*')? 'active': ''}}">
            <a href="#"><i class="fa fa-book"></i> <span>Catálogos Generales</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>

            <ul class="treeview-menu">
                @role('Super-Administrador|Administrador')
                <li class="{{request()->is('proveedores')? 'active': ''}}">
                    <a href="{{route('proveedores.index')}}">
                        <i class="fa fa-table"></i>Proveedores</a>
                </li>
                @endrole

                @role('Super-Administrador|Administrador|Vendedor')
                <li class="{{request()->is('territorios')? 'active': ''}}"><a href="{{route('territorios.index')}}">
                        <i class="fa fa-table"></i>Territorios</a>
                </li>
                @endrole

                @role('Super-Administrador|Administrador|Vendedor')
                <li class="{{request()->is('clientes')? 'active': ''}}"><a href="{{route('clientes.index')}}">
                        <i class="fa fa-table"></i>Clientes</a>
                </li>
                @endrole

                @role('Super-Administrador|Administrador')
                <li class="{{request()->is('bodegas')? 'active': ''}}"><a href="{{route('bodegas.index')}}">
                        <i class="fa fa-table"></i>Bodegas</a>
                </li>
                @endrole

                @role('Super-Administrador|Administrador')
                <li class="{{request()->is('formas_pago')? 'active': ''}}"><a href="{{route('formas_pago.index')}}">
                        <i class="fa fa-table"></i>Formas de Pago</a>
                </li>
                @endrole

                @role('Super-Administrador|Administrador')
                <li class="{{request()->is('presentaciones_producto')? 'active': ''}}"><a href="{{route('presentaciones_producto.index')}}">
                        <i class="fa fa-table"></i>Presentaciones de Producto</a>
                </li>
                @endrole

                @role('Super-Administrador|Administrador')
                <li class="{{request()->is('productos')? 'active': ''}}"><a href="{{route('productos.index')}}">
                        <i class="fa fa-table"></i>Productos</a>
                </li>
                @endrole
            </ul>

        </li>
        @endrole

        @role('Super-Administrador|Administrador|Contador')
        <li class="{{request()->is('compras')? 'active': ''}}">
            <a href="{{route('compras.index')}}">
                <i class="fas fa-cash-register"></i>
                <span>&nbsp Compras</span>
            </a>
        </li>
        @endrole

        @role('Super-Administrador|Administrador|Vendedor')
        <li class="{{request()->is('pedidos')? 'active': ''}}"><a href="{{route('pedidos.index')}}">
                <i class="fas fa-clipboard-list"></i>
                <span>&nbsp Pedidos</span>
            </a>
        </li>
        @endrole

        @role('Super-Administrador|Administrador|Vendedor')
        <li class="{{request()->is('notas_envio')? 'active': ''}}"><a href="{{route('notas_envio.index')}}">
                <i class="fas fa-share-square"></i>
                <span>&nbsp Notas de Envío</span>
            </a>
        </li>
        @endrole

        @role('Super-Administrador|Administrador')
        <li class="{{request()->is('traspasos_bodega')? 'active': ''}}"><a href="{{route('traspasos_bodega.index')}}">
                <i class="fas fa-exchange-alt"></i>
                <span>&nbsp Traspasos de Bodega</span>
            </a>
        </li>
        @endrole


        @role('Super-Administrador|Administrador')
        <li class="{{request()->is('partidas_ajuste')? 'active': ''}}">
            <a href="{{route('partidas_ajuste.index')}}">
                <i class="fas fa-exchange-alt fa-rotate-90"></i>
                <span>&nbsp Partidas de Ajuste</span>
            </a>
        </li>
        @endrole


        @role('Super-Administrador|Administrador|Vendedor')
        <li class="{{request()->is('cuentas_cobrar')? 'active': ''}}">
            <a href="{{route('cuentas_cobrar.index')}}">
                <i class="fas fa-hand-holding-usd"></i>
                <span>&nbsp Cuentas por Cobrar</span>
            </a>
        </li>
        @endrole

        @role('Super-Administrador|Administrador')
        <li class="{{request()->is('cuentas_pagar')? 'active': ''}}">
            <a href="{{route('cuentas_pagar.index')}}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>&nbsp Cuentas por Pagar</span>
            </a>
        </li>
        @endrole

        @role('Super-Administrador|Administrador|Vendedor')
        <li class="{{request()->is('visitas')? 'active': ''}}"><a href="{{route('visitas.index')}}">
                <i class="fas fa-check-double"></i>
                <span>&nbsp Registro de Visitas</span>
            </a>
        </li>
        <li>
            <a href="#" data-toggle="modal" data-target="#modal_ventas"><i class="fa fa-file-alt"></i>Ventas por Vendedor</a>
        </li>
        @endrole
        @role('Super-Administrador|Administrador|Contador')
        <li class="treeview {{request()->is('facturas*')? 'active': ''}}">
            <a href="#"><i class="fa fa-receipt"></i> <span>Facturas</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>

            <ul class="treeview-menu">

                <li class="{{request()->is('facturas')? 'active': ''}}">
                    <a href="{{route('facturas.index')}}">
                        <i class="fas fa-table"></i>
                        <span>&nbsp Ventas por factura</span>
                    </a>
                </li>

            </ul>
        </li>
        @endrole


        @role('Super-Administrador|Administrador|Vendedor')
        <li class="treeview">
            <a href="#"><i class="fa fa-chart-line"></i><span>Reportes</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" id="mix_max_report"><i class="fa fa-file-alt"></i>Máximos y Mínimos en Stock</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" id="stock_report"><i class="fa fa-file-alt"></i>Inventario general</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador|Vendedor')
                <li>
                    <a href="#" id="warehouse_stock_report"><i class="fa fa-file-alt"></i>Inventario por bodega</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" id="expiration_report"><i class="fa fa-file-alt"></i>Productos por vencerse</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_client_balance"><i class="fa fa-file-alt"></i>Saldos Clientes</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#"  data-toggle="modal" data-target="#modal_saldos_territorios"><i class="fa fa-file-alt" target="_blank"></i>Saldos Clientes por Territorio</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador|Vendedor')
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_ventas"><i class="fa fa-file-alt"></i>Ventas por Vendedor</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_proveedores"><i class="fa fa-file-alt"></i>Saldo de Proveedores</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_compras"><i class="fa fa-file-alt"></i>Reporte de compras</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_traspasoBodegas"><i class="fa fa-file-alt"></i>Traspaso entre bodegas</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_reporteMensual"><i class="fa fa-file-alt"></i>Movimiento Mensual</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_liquidacion"><i class="fa fa-file-alt"></i>Liquidación Mensual</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_ganancias"><i class="fa fa-file-alt"></i>Reporte de Ganancias</a>
                </li>
                @endrole
                @role('Super-Administrador|Administrador')
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal_Abonos"><i class="fa fa-file-alt"></i>Abonos de Clientes</a>
                </li>
                @endrole
            </ul>
        </li>
        @endrole



        @role('Super-Administrador|Administrador|Vendedor')
        <li class="treeview {{request()->is('users*')? 'active': ''}}">
          <a href="#"><i class="fa fa-users"></i> <span>Gestion Usuarios</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              @role('Super-Administrador|Administrador')
            <li class="{{request()->is('users')? 'active': ''}}"><a href="{{route('users.index')}}">
              <i class="fa fa-eye"></i>Usuarios</a>
            </li>
            @endrole
            @role('Super-Administrador|Administrador|Vendedor')
            <li>
                <a href="#" data-toggle="modal" data-target="#modalResetPassword"><i class="fa fa-lock-open"></i>Cambiar contraseña</a>
            </li>
            @endrole

          </ul>
        </li>
        @endrole


        @role('Super-Administrador|Administrador')

        <li class="treeview {{request()->is('negocio*')? 'active': ''}}">
            <a href="#"><i class="fa fa-building"></i> <span>Mi Negocio</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>

            <ul class="treeview-menu">
                <li class="{{request()->routeIs('negocio.edit')? 'active': ''}}"><a href="{{route('negocio.edit', 1)}}">
                        <i class="fa fa-edit"></i>Editar Mi Negocio</a>
                </li>
            </ul>
        </li>
        @endrole


    </ul>

    <!-- /.sidebar-menu -->
