@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
        Ventas por Factura
        <small>Todas las Facturas</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Ventas por Factura</li>
    </ol>
</section>

@endsection

@section('content')
@include('admin.facturas.confirmarAnulacionModal')
@include('admin.facturas.create')
<div class="loader loader-bar is-active"></div>
<div class="box">
        @role('Super-Administrador|Administrador')
        <div class="box-header">
            <div class="text-right">
                <button  data-target='#modalFactura' data-toggle='modal' id="print_receipt" class="btn btn-primary pull-right"
                    style="margin-top: 15px; margin-bottom: 10px">Crear Nueva Factura</button>
            </div>
        </div>
        @endrole
    <div class="box-body">
        <input type="hidden" name="rol_user" value="{{auth()->user()->roles[0]->name}}">
        <table id="facturas-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"
            width="100%">
        </table>
        <input type="hidden" id="urlActual" value="{{url()->current()}}">
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

<input type="hidden" id='fac_id'>
<input type="hidden" id='fac_fecha'>
<input type="hidden" id='fac_serie'>
<input type="hidden" id='fac_no'>
<input type="hidden" id='fac_sub'>
<input type="hidden" id='fac_tax'>
<input type="hidden" id='fac_total'>
<input type="hidden" id='fac_nombre'>
<input type="hidden" id='fac_cliente_id'>
<input type="hidden" id='fac_dias'>
<input type="hidden" id='fac_nit'>
<input type="hidden" id='fac_address'>
<input type="hidden" id='fac_tel'>
<input type="hidden" id='fac_seller'>



@endsection


@push('styles')


@endpush

@push('scripts')
<script>

    $(document).ready(function () {
        $('.loader').fadeOut(225);
        //loads the datatable
        facturas_table.ajax.url("{{route('facturas.getJson')}}").load();
    });

</script>
<script src="{{asset('js/facturas/index.js')}}"></script>
<script src="{{asset('js/pedidos/numero_letras.js')}}"></script>
@endpush
