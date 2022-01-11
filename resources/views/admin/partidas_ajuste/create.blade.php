@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
        Partidas de Ajuste
        <small>Registrar una Partida de ajuste a la bodega {{$bodega->nombre}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li><a href="{{route('partidas_ajuste.index')}}"><i class="fa fa-list"></i> Partidas de ajuste</a></li>
        <li class="active">Crear</li>
    </ol>
</section>
@stop

@section('content')
<form method="POST" id="PartidaForm" action="{{route('pedidos.save')}}">
    {{csrf_field()}}
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="cliente_id">Tipo de Ajuste:</label>
                        <select class="form-control" id="tipos-ajuste" autofocus tabindex="1">
                            <option value="default">seleccione un tipo</option>
                            <option value="1">Ingreso</option>
                            <option value="2">Salida</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="codigo_producto">Bodega:</label>
                        <input type="text" class="form-control customreadonly" id="nombre-bodega" placeholder="Nombre de la bodega" value="{{$bodega->nombre}}">
                        <input type="hidden" name="id_bodega" value="{{$bodega->id}}">
                    </div>
                    <div class="col-sm-3">
                        <label for="cantidad">Estado:</label>
                        <input type="text" class="form-control customreadonly" id="estado-bodega" value="@if($bodega->estado == 1) Activa @else Inactiva @endif">
                    </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-sm-2">
                      <label for="switch">¿Buscar por código?</label>
                        <div class="switch-field">
                          <input type="radio"   class="form-control customreadonly" id="radio-uno" name="sw" value="false" />
                          <label for="radio-uno">No</label>
                          <input type="radio"  class="form-control customreadonly"id="radio-dos" name="sw" value="true" checked />
                          <label for="radio-dos">Si</label>
                        </div>
                  </div>
                    <div class="col-sm-4">
                        <label for="nombre_comercial">Buscar Producto:</label>
                        <input type="text" class="form-control" id="codigo-producto" tabindex="2" placeholder="Codigo del producto">
                        <input list="browsers" id="lista" style="display:none" class="form-control" name="codigo_producto"  placeholder="Nombre  del producto">
                        <datalist id="browsers">
                          @foreach ($productos as $p)
                              <option value="{{$p->nombre_comercial}}">
                          @endforeach
                        </datalist>
                        <input type="hidden" id="producto-id">
                    </div>
                    <div class="col-sm-3">
                        <label for="presentacion">Cantidad:</label>
                        <input type="number" class="form-control" id="cantidad" tabindex="3">
                    </div>
                    <div class="col-sm-3">
                        <label for="precio">Precio:</label>
                        <div class="input-group">
                            <span class="input-group-addon">Q.</span>
                            <input type="number" class="form-control" placeholder="Precio del producto" id="precio" tabindex="4">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="serie">Nombre del Producto:</label>
                        <input type="text" class="form-control customreadonly" id="nombre-producto" placeholder="Nombre comercial del producto">
                    </div>
                    <div class="col-sm-3">
                        <label for="no-factura">Subtotal:</label>
                        <input type="text" class="form-control customreadonly" id="subtotal" placeholder="Subtotal del ajuste">
                    </div>
                    <div class="col-sm-3">
                        <label for="Existencias">Existencias:</label>
                        <input type="text" class="form-control customreadonly" id="existencias" placeholder="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="text-left m-t-15">
                            <h3>Detalle</h3>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-right m-t-15" style="margin-top: 15px; margin-bottom: 10px">
                            <button id="agregar-detalle" class="btn btn-success form-button" tabindex="5">Agregar al detalle</button>
                        </div>
                    </div>
                </div>
                <br>
                <table id="detalle-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap" width="100%">
                </table>
                <br>
                <div class="row">
                    <div class="col-sm-4">
                        <label for="total_ingreso">Total de Ingreso:</label>
                        <div class="input-group">
                            <span class="input-group-addon">Q.</span>
                            <input type="text" class="form-control customreadonly" placeholder="Total de ingresos" name="total_ingreso" id="total_i">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label for="total_ingreso">Total de Salida:</label>
                        <div class="input-group">
                            <span class="input-group-addon">Q.</span>
                            <input type="text" class="form-control customreadonly" placeholder="Total de salidas" name="total_salida" id="total_s">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label for="total_ingreso">Diferencia:</label>
                        <div class="input-group">
                            <span class="input-group-addon">Q.</span>
                            <input type="text" class="form-control customreadonly" placeholder="Diferencia de los totales" name="saldo" id="saldo">
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-right m-t-15">
                    <a class='btn btn-primary form-button' href="{{ route('partidas_ajuste.index') }}">Regresar</a>
                    <button id="ButtonPartida" class="btn btn-success form-button">Guardar</button>
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
    .customreadonly1{
        background-color: #eee;
        pointer-events: none;
    }
    .switch-field {
        display: flex;
        margin-bottom: 36px;
        overflow: hidden;
    }

    .switch-field input {
        position: absolute !important;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        width: 1px;
        border: 0;
        overflow: hidden;
    }

    .switch-field label {
        background-color: #e4e4e4;
        color: rgba(0, 0, 0, 0.6);
        font-size: 14px;
        line-height: 1;
        text-align: center;
        padding: 8px 16px;
        margin-right: -1px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        /* box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1); */
        transition: all 0.1s ease-in-out;
    }

    .switch-field label:hover {
        cursor: pointer;
    }

    .switch-field input:checked + label {
        background-color: #55bd8c;
        box-shadow: none;
    }

    .switch-field label:first-of-type {
        border-radius: 4px 0 0 4px;
    }

    .switch-field label:last-of-type {
        border-radius: 0 4px 4px 0;
    }
</style>

@endpush


@push('scripts')

<script>
    $(document).ready(function(){
        $('#tipos-ajuste').rules('add', {select: 'default'});
    })

    //gets the selected prduct data and sets the readonly inputs
    $(document).on('focusout', '#codigo-producto', function() {
        var codigo = $('#codigo-producto').val();
        var url = "@php echo url('/') @endphp" + "/pedidos/getProductoData/" + codigo + "/{{$bodega->id}}";
        $('#nombre-producto-error').remove();
        $('#nombre-producto').val(null);
        $('#producto-id').val(null);
        $('#precio').val(null);
        $.ajax({
            url: url,
            success: function(data) {
                if (data[0]) {
                    $('#nombre-producto').val(data[0].nombre_comercial);
                    $('#producto-id').val(data[0].id);
                    $('#precio').val(data[0].precio_venta);
                } else {
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.error(
                        'No se encontraron existencias del producto en la bodega {{$bodega->nombre}}.');
                    $('#nombre-producto').val(null);
                    $('#producto-id').val(null);
                    $('#precio').val(null);
                }
            },
            error: function() {
                alertify.set('notifier', 'position', 'top-center');
                alertify.error(
                    'Hubo un error al buscar el producto. Asegúrese de que el código está correctamente escrito');
                $('#nombre-producto').val(null);
                $('#producto-id').val(null);
            }
        });
    });

    //calculates subtotal
    $(document).on('focusout', '#cantidad, #precio', function() {
        $('#subtotal').val(null);
        let sub = parseFloat($('#precio').val()) * parseFloat($('#cantidad').val());
        sub = sub.toFixed(2);
        $('#subtotal').val(sub);
    });

    //checks for empty fields
    function chkflds() {
        if ($('#nombre-producto').val() && $('#cantidad').val() && $('#precio').val() && $('#subtotal').val()) {
            return true
        } else {
            return false
        }
    }
var v = [];
var id;
var t = 0;
    $('#agregar-detalle').click(function(e) {
        e.preventDefault();
        let tipo = parseInt($('#tipos-ajuste').val());
        if (chkflds()) {
            if(tipo == 1){
                //adds the form data to the table
                detalle_table.row.add({
                    'tipo_ajuste': $('#tipos-ajuste').val(),
                    'id_producto': $('#producto-id').val(),
                    'nombre_producto': $('#nombre-producto').val(),
                    'cantidad': $('#cantidad').val(),
                    'precio': $('#precio').val(),
                    'ingreso': $('#subtotal').val(),
                    'salida': 0
                }).draw();
              id =  $('#producto-id').val();
              v[id] = parseInt($('#existencias').val()) + parseInt($('#cantidad').val());
              //console.log(v[id]);
            }else{
              id =  $('#producto-id').val();
              t = parseInt($('#existencias').val()) - parseInt($('#cantidad').val());
              if (t >= 0) {
                  detalle_table.row.add({
                      'tipo_ajuste': $('#tipos-ajuste').val(),
                      'id_producto': $('#producto-id').val(),
                      'nombre_producto': $('#nombre-producto').val(),
                      'cantidad': $('#cantidad').val(),
                      'precio': $('#precio').val(),
                      'ingreso': 0,
                      'salida': $('#subtotal').val()
                  }).draw();
                  v[id] = parseInt($('#existencias').val()) - parseInt($('#cantidad').val());
              }else {
                alertify.set('notifier', 'position', 'top-center');
                alertify.error('La cantidad ingresada es mayor a las existencias.')
              }



            }
            //adds all ingreso row data and sets the total_ingreso input
            var total_ingreso = 0;
            detalle_table.column(5).data().each(function(value, index) {
                total_ingreso = total_ingreso + parseFloat(value);
                parseFloat(total_ingreso).toFixed(2);
                $('#total_i').val(total_ingreso);
                $('#total_i-error').remove();
            });

            //adds all salida row data and sets the total_salida input
            var total_salida = 0;
            detalle_table.column(6).data().each(function(value, index) {
                total_salida = total_salida + parseFloat(value);
                parseFloat(total_salida).toFixed(2);
                $('#total_s').val(total_salida);
                $('#total_s-error').remove();
            });

            var saldo = parseFloat(total_ingreso - total_salida).toFixed(2);
            $('#saldo').val(saldo);
            //resets form data
            $('#codigo-producto').val(null);
            $('#nombre-producto').val(null);
            $('#cantidad').val(null);
            $('#precio').val(null);
            $('#subtotal').val(null);
            $('#existencias').val(null);
        } else {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error('Debe seleccionar un producto, cantidad y precio.')
        }
    });

    $(document).on('click', '#ButtonPartida', function(e) {
        e.preventDefault();
        if ($('#PartidaForm').valid()) {
            $('.loader').addClass('is-active');
            $('#ButtonPartida').prop('disabled', true);
            var arr1 = $('#PartidaForm').serializeArray();
            var arr2 = detalle_table.rows().data().toArray();
            var arr3 = arr1.concat(arr2);

            $.ajax({
                type: 'POST'
                , url: "{{route('partidas_ajuste.save')}}"
                , headers: {
                    'X-CSRF-TOKEN': $('#tokenReset').val()
                , }
                , data: JSON.stringify(arr3)
                , dataType: 'json'
                , success: function() {
                    $('#codigo-producto').val(null);
                    $('#nombre-producto').val(null);
                    $('#cantidad').val(null);
                    $('#precio').val(null);
                    $('#subtotal').val(null);
                    detalle_table.rows().remove().draw();
                    window.location.assign('/partidas_ajuste?ajaxSuccess')
                }
                , error: function() {
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.error('Hubo un error al registrar la partida de ajuste.')
                }
            })
        }
    });

</script>
<script type="text/javascript">
var contador = 0;

//gets the selected prduct data and sets the readonly inputs
$(document).on('focusout', '#codigo-producto', function(){
    var codigo = $('#codigo-producto').val();
    var url = "@php echo url('/') @endphp" + "/pedidos/getProductoData/" + codigo + "/{{$bodega->id}}";
    $('#nombre-com-error').remove();
    $('#presentacion-error').remove();
    $('#nombre-com').val(null);
    $('#presentacion').val(null);
    $('#producto-id').val(null);
    $('#precio').val(null);
    $('#stock').val(null);
    $.ajax({
        url: url,
        success: function(data){
          if (v[$('#producto-id').val()] == null) {
            contador = 0;
          }
            if(data[0]){
              if (contador == 0) {
                  $('#existencias').val(data[0].existencias);
                  contador += 1;
              }else {
                  $('#existencias').val(v[$('#producto-id').val()]);
                //  console.log("verificando" + v[$('#producto-id').val()]);
              }

            } else {
                alertify.set('notifier', 'position', 'top-center');
                alertify.error  (
                    'No se encontraron existencias del producto en la bodega {{$bodega->nombre}}.');
                    $('#nombre-com').val(null);
                    $('#presentacion').val(null);
                    $('#producto-id').val(null);
            }
        },
        error: function(){
            alertify.set('notifier', 'position', 'top-center');
            alertify.error  (
                'Hubo un error al buscar el producto. Asegúrese de que el código está correctamente escrito');
                $('#nombre-com').val(null);
                $('#presentacion').val(null);
                $('#producto-id').val(null);
            }
        });
    });
</script>
<script>
$(document).on('click', '#radio-uno', function(){
    if(this.checked == true){
      $('#codigo-producto').css('display', 'none');
      $('#lista').css('display', 'inline');

        //$('#codigo-producto').type('text');
        //$('#lista').type('hidden');


    }
});
$(document).on('click', '#radio-dos', function(){
    if(this.checked == true){

      $('#codigo-producto').css('display', 'inline');
      $('#lista').css('display', 'none');
    //  $('#codigo-producto').type('hidden');
      //$('#lista').type('text');

    }
});

//gets the selected prduct data and sets the readonly inputs
$(document).on('focusout', '#lista', function() {
    var codigo = encodeURI($('#lista').val());
    var url = "@php echo url('/') @endphp" + "/pedidos/getProductoData1/" + codigo + "/{{$bodega->id}}";
    $('#nombre-producto-error').remove();
    $('#nombre-producto').val(null);
    $('#producto-id').val(null);
    $('#precio').val(null);
    $.ajax({
        url: url,
        success: function(data) {
            if (data[0]) {
                $('#nombre-producto').val(data[0].nombre_comercial);
                $('#producto-id').val(data[0].id);
                $('#precio').val(data[0].precio_venta);
            } else {
                alertify.set('notifier', 'position', 'top-center');
                alertify.error(
                    'No se encontraron existencias del producto en la bodega {{$bodega->nombre}}.');
                $('#nombre-producto').val(null);
                $('#producto-id').val(null);
                $('#precio').val(null);
            }
        },
        error: function() {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error(
                'Hubo un error al buscar el producto. Asegúrese de que el código está correctamente escrito');
            $('#nombre-producto').val(null);
            $('#producto-id').val(null);
        }
    });
});

//gets the selected prduct data and sets the readonly inputs
$(document).on('focusout', '#lista', function(){
    var codigo = encodeURI($('#lista').val());
    var url = "@php echo url('/') @endphp" + "/pedidos/getProductoData1/" + codigo + "/{{$bodega->id}}";
    $('#nombre-com-error').remove();
    $('#presentacion-error').remove();
    $('#nombre-com').val(null);
    $('#presentacion').val(null);
    $('#producto-id').val(null);
    $('#precio').val(null);
    $('#stock').val(null);
    $.ajax({
        url: url,
        success: function(data){
          if (v[$('#producto-id').val()] == null) {
            contador = 0;
          }
            if(data[0]){
              if (contador == 0) {
                  $('#existencias').val(data[0].existencias);
                  contador += 1;
              }else {
                  $('#existencias').val(v[$('#producto-id').val()]);
                //  console.log("verificando" + v[$('#producto-id').val()]);
              }

            } else {
                alertify.set('notifier', 'position', 'top-center');
                alertify.error  (
                    'No se encontraron existencias del producto en la bodega {{$bodega->nombre}}.');
                    $('#nombre-com').val(null);
                    $('#presentacion').val(null);
                    $('#producto-id').val(null);
            }
        },
        error: function(){
            alertify.set('notifier', 'position', 'top-center');
            alertify.error  (
                'Hubo un error al buscar el producto. Asegúrese de que el código está correctamente escrito');
                $('#nombre-com').val(null);
                $('#presentacion').val(null);
                $('#producto-id').val(null);
            }
        });
    });
</script>
<script src="{{asset('js/partidas_ajuste/new.js')}}"></script>{{-- datatable --}}
<script src="{{asset('js/partidas_ajuste/create.js')}}"></script>{{-- validator --}}
@endpush
