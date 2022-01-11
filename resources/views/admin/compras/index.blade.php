@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
      Listado de Compras
      <small>Todas las Compras</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active">Compras</li>
    </ol>
  </section>

  @endsection

@section('content')
@include('admin.users.confirmarAccionModal')
<div class="loader loader-bar is-active"></div>
<div class="box">
    <div class="box-header">
      <a class="btn btn-primary pull-right" href="{{route('compras.new')}}">
        <i class="fa fa-plus"></i>Agregar Compra</a>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <input type="hidden" name="rol_user" value="{{auth()->user()->roles[0]->name}}">
        <table id="compras-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">            
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
      compras_table.ajax.url("{{route('compras.getJson')}}").load();
      if (window.location.href.indexOf("ajaxSuccess") > -1) {
          alertify.set('notifier', 'position', 'top-center');
          alertify.success('La compra se ha registrado exitosamente.')
          window.history.replaceState({}, document.title, "/compras");
        }else if(window.location.href.indexOf("lastDetail") > -1){
          alertify.set('notifier', 'position', 'top-center');
          alertify.warning('La compra no contienen registros y ha sido eliminada.')
          window.history.replaceState({}, document.title, "/compras");
        }
    });

  </script>
  <script src="{{asset('js/compras/index.js')}}"></script>
@endpush