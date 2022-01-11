@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
        Listado de Notas de Envío
        <small>Todas los Notas de Envío</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Notas de Envío</li>
    </ol>
</section>

@endsection

@section('content')
@include('admin.users.confirmarAccionModal')
@include('admin.notas_envio.modal_editar')
<div class="loader loader-bar is-active"></div>
<div class="box">
    <div class="box-header">
        <a class="btn btn-primary pull-right" href="{{route('notas_envio.new')}}">
            <i class="fa fa-plus"></i> Agregar Nota de Envío</a>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <input type="hidden" name="rol_user" value="{{auth()->user()->roles[0]->name}}">
        <table id="notas-envio-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"
            width="100%">
        </table>
        <input type="hidden" id="urlActual" value="{{url()->current()}}">
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

@endsection


@push('styles')


@endpush

@push('scripts')
<script src="{{asset('js/pedidos/numero_letras.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.loader').fadeOut(225);
        //loads the datatable
        notas_envio_table.ajax.url("{{route('notas_envio.getJson')}}").load();
        //check if the insert view sent a message
        if (window.location.href.indexOf("ajaxSuccess") > -1) {
            alertify.set('notifier', 'position', 'top-center');
            alertify.success('La nota de envío se ha registrado exitosamente.')
            window.history.replaceState({}, document.title, "/notas_envio");
        }
    });

</script>
<script src="{{asset('js/notas_envio/index.js')}}"></script>
@endpush