@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
      Listado de Presentaciones de Producto
      <small>Todos los presentaciones de producto</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active">Presentaciones de Producto</li>
    </ol>
  </section>

  @endsection

@section('content')
@include('admin.presentaciones_producto.modalEditar')
@include('admin.presentaciones_producto.modalInsertar')
@include('admin.users.confirmarAccionModal')
<div class="loader loader-bar is-active"></div>
<div class="box">
  <div class="box-header">
      <a class="btn btn-primary pull-right" data-target='#modalInsertar' data-toggle='modal' id='btn-insertar'>
        <i class="fa fa-plus"></i>Agregar Presentación de Producto</a>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <input type="hidden" name="rol_user" value="{{auth()->user()->roles[0]->name}}">
        <table id="presentaciones-producto-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">            
        </table>
        <input type="hidden" name="urlActual" value="{{url()->current()}}"> 
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box --> 
    
    @endsection


@push('styles')
 
 
@endpush

@push('scripts')
  <script>
    $(document).ready(function() {
      $('.loader').fadeOut(225);
      presentaciones_producto_table.ajax.url("{{route('presentaciones_producto.getJson')}}").load();
    });

  </script>
  <script src="{{asset('js/presentaciones_producto/index.js')}}"></script>
@endpush