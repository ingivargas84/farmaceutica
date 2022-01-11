@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
        PEDIDOS
        <small>Detalle del Pedido</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li><a href="{{route('pedidos.index')}}"><i class="fa fa-list"></i> Pedidos</a></li>
        <li class="active">Detalle</li>
    </ol>
</section>
@stop
@section('content')
@include('admin.devoluciones.modalEditar')
<form method="POST" id="DevolucionForm"  action="">
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
              <div class="col-sm-3">
                <label for="bodega">Seleccione Bodega:</label>
                <select name="bodega" class="form-control" id="bodega">
                    <option value="default" >Seleccione una Bodega</option>
                    @foreach ($bodega as $b)
                        <option value="{{$b->id}}">{{$b->nombre}}</option>
                    @endforeach
                </select>
                <input type="hidden" name="id" value="{{$id}}" id="id">
                  @foreach ($cliente as $c)
                <input type="hidden" name="cliente" value="{{$c->cliente_id}}" id="cliente">
                        @endforeach
              </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="text-left">
                        <h3 class="display-5"><strong>Detalle</strong></h3>
                    </div>
                </div>
                </div>
            <table id="detalles-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"
                width="100%">
            </table>
            <br>
            <div class="text-right m-t-15">

                <button id="ButtonDevolucion" class="btn btn-success form-button">Guardar</button>
            </div>
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
    $(document).ready(function () {
        var codigo = $('#id').val();
        $('.loader').fadeOut(225);
        detalles_table.ajax.url("devoluciones/devolucion/" + codigo + "/").load();
    });


    $(document).ready(function(){
      var codigo = $('#id').val();
            var url =  "devoluciones/devolucion/" + codigo + "/";
        $.ajax({
            url: url,
              success: function(data){
                for (var i=0; i<data.length;i++){
                  detalles_table.row.add({
                      'producto': data[i].producto,
                      'cantidad': data[i].cantidad,
                      'precio': data[i].precio,
                      'subtotal': data[i].subtotal,
                      'idproducto': data[i].idproducto,
                  }).draw();

                }
        },
        error: function(){
          alertify.set('notifier', 'position', 'top-center');
          alertify.error  ('Error al cargar detelle pedido');
        }
      });
    });


    $(document).one('click', '#ButtonDevolucion', function(e){
        e.preventDefault();

            var arr1 = $('#DevolucionForm').serializeArray();
            var arr2 = datos;
            var arr3 = arr1.concat(arr2);
              if ($('#DevolucionForm').valid()) {
            $.ajax({
                type: 'POST',
                url: "{{route('pedidos.devoluciones')}}",
                headers:{'X-CSRF-TOKEN': $('#tokenReset').val(),},
                data: JSON.stringify(arr3),
                dataType: 'json',
                success: function(){
                detalles_table.rows().remove().draw();
                window.location.assign('/pedidos')
                },
                error: function(){
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.error('Hubo un error al realizar devolución');
                }
            })
          }
    });
</script>
<script src="{{asset('js/devoluciones/index.js')}}"></script>{{-- validator --}}
<script src="{{asset('js/devoluciones/indexValidaciones.js')}}"></script>{{-- validator --}}
@endpush
