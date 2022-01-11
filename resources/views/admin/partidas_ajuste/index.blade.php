@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
        Listado de Partidas de Ajuste
        <small>Todas los partidas de ajuste</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Partidas de Ajuste</li>
    </ol>
</section>

@endsection

@section('content')
@include('admin.users.confirmarAccionModal')
@include('admin.partidas_ajuste.modal')
<div class="loader loader-bar is-active"></div>
<div class="box">
    <div class="box-header">
        <a class="btn btn-primary pull-right" id="new_adjustment_button" href="#">
            <i class="fa fa-plus"></i> Agregar Partida de Ajuste</a>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <input type="hidden" name="rol_user" value="{{auth()->user()->roles[0]->name}}">
        <table id="partidas-ajuste-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"
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

    //show the warehouse selection modal to create an order
    $(document).on('click', '#new_adjustment_button', function() {
        $('#new_adjustment_modal').modal('toggle');
        cargarBodegas();
    });

    function cargarBodegas() {
        $.ajax({
            url:"{{route('bodegas.getJson')}}"
        }).then(function (data){
            console.log(data.data[0].nombre)
            var cuenta = 0;
            $("#bodegas_select").empty();
            //this block adds a default option for validation
            var op = document.createElement("OPTION");
            op.append('------------');
            op.setAttribute("value", 'default');
            $("#bodegas_select").append(op);
            //this block adds the options from the ajax request
            while (cuenta < data.data.length) {
                var op = document.createElement("OPTION");
                op.append(data.data[cuenta].nombre);
                op.setAttribute("value", data.data[cuenta].id);
                $("#bodegas_select").append(op);
                cuenta ++;
            }
        })
    }


    $(document).ready(function () {
        $('.loader').fadeOut(225);
        //loads the datatable
        partidas_ajuste_table.ajax.url("{{route('partidas_ajuste.getJson')}}").load();
        //check if the insert view sent a message
        if (window.location.href.indexOf("ajaxSuccess") > -1) {
            alertify.set('notifier', 'position', 'top-center');
            alertify.success('La partida de ajuste se ha registrado exitosamente.')
            window.history.replaceState({}, document.title, "/partidas_ajuste");
        }
    });

</script>
<script src="{{asset('js/partidas_ajuste/index.js')}}"></script>
@endpush