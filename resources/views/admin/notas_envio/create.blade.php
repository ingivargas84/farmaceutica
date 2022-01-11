@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          Notas de Envío
          <small>Registrar una Nota de Envío</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('notas_envio.index')}}"><i class="fa fa-list"></i> Notas de Envío</a></li>
          <li class="active">Crear</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="NotaEnvioForm"  action="{{route('pedidos.save')}}">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="pedidos">Pedido:</label>
                                <select name="pedidos" class="form-control" id="pedidos" autofocus tabindex="1">
                                    <option value="default" >Seleccione un Pedido</option>
                                    {{-- aquí se cargarán los pedidos mediante ajax --}}
                                    @if (sizeOf($pedidos) > 0)
                                        @foreach ($pedidos as $pedido)
                                            <option value="{{$pedido->id}}"> {{$pedido->fecha_ingreso}} | {{$pedido->no_pedido}}    {{$pedido->nombre_cliente}}</option>
                                        @endforeach
                                    @else
                                        <option value="default">No hay pedidos sin nota de envío.</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="cliente">Cliente:</label>
                                <input type="text" class="form-control" id="cliente" name="cliente" tabindex="2" placeholder="Nombre del cliente">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="direccion">Direción:</label>
                                <input type="text" class="form-control" placeholder='Dirección del cliente.' name="direccion" id="direccion" tabindex='3'>
                            </div>
                            <div class="col-sm-6">
                                <label for="telefono">Teléfono:</label>
                                <input type="text" class="form-control" placeholder='Teléfono del cliente' name="telefono" id="telefono" tabindex='4'>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-left m-t-15">
                                    <h3>Detalle</h3>
                                </div>
                            </div>
                        </div>
                        <br>
                        <table id="detalle-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">
                        </table>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="total_ingreso">Total:</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Q.</span>
                                    <input type="text" class="form-control customreadonly" placeholder="Total del Pedido" name="total_ingreso" id="total">
                                </div>
                            </div>
                        </div>
                            <div class="text-right m-t-15">
                                <a class='btn btn-primary form-button' href="{{ route('pedidos.index') }}">Regresar</a>
                                <button id="ButtonNotaEnvio" class="btn btn-success form-button">Guardar</button>
                            </div>
                        <br>

                    </div>
                </div>
            </div>
    </form>
    <div class="loader loader-bar"></div>
@stop


@push('styles')

<style>
    .customreadonly{
        background-color: #eee;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>

@endpush


@push('scripts')

<script>

$(document).ready(function(){
    //turn #pedidos into a search capable dropdown
    $('#pedidos').select2();
})

$(document).on('change', '#pedidos', function(){
    if($('#pedidos').val() == 'default'){
        $('#telefono').val('');
        $('#direccion').val('');
        $('#cliente').val('');
        $('#total').val('');
        detalle_table.clear().draw();
    }else{
        var pedidoUrl = 'http://' + window.location.host + "/notas_envio/getPedidoData/" + $('#pedidos').val();
        var detailUrl = 'http://' + window.location.host + "/pedidos/" +  $('#pedidos').val() + "/getDetalles/";
        //get the pedido data and set it into the inputs
        $.get(pedidoUrl, function(data){
            $('#direccion').val(data[0].direccion);
            $('#telefono').val(data[0].telefono_compras);
            $('#cliente').val(data[0].nombre_cliente);
            $('#total').val(data[0].total);
        });
        detalle_table.ajax.url(detailUrl).load();
        $('#pedidos-error').remove();
    }
});

$(document).on('click', '#ButtonNotaEnvio', function(e){
    e.preventDefault();

    if ($('#NotaEnvioForm').valid()) {
        $("#ButtonNotaEnvio").attr("disabled", true);
        $.ajax({
            type: 'POST',
            url: "{{route('notas_envio.save')}}",
            headers:{'X-CSRF-TOKEN': $('#tokenReset').val(),},
            data: $('#NotaEnvioForm').serializeArray(),
            dataType: 'json',
            success: function(){
                $('#telefono').val('');
                $('#direccion').val('');
                $('#cliente').val('');
                $('#total').val('');
                detalle_table.clear().draw();
                window.location.assign('/notas_envio?ajaxSuccess')
            },
            error: function(){
                alertify.set('notifier', 'position', 'top-center');
                alertify.error('Hubo un error al registrar la nota de envío')
            }
        })
    }
});
</script>

<script src="{{asset('js/notas_envio/new.js')}}"></script>{{-- datatable --}}
<script src="{{asset('js/notas_envio/create.js')}}"></script>{{-- validator --}}
@endpush
