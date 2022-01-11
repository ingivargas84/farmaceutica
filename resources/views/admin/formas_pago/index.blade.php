@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
      Listado de Formas de Pago
      <small>Todos los formas de pago</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active">Formas de Pago</li>
    </ol>
  </section>

  @endsection

@section('content')
@include('admin.formas_pago.modalEditar')
@include('admin.formas_pago.modalInsertar')
@include('admin.users.confirmarAccionModal')
<div class="loader loader-bar is-active"></div>
<div class="box">
  <div class="box-header">
    {{-- <a class="btn btn-primary pull-right" href="{{route('bodegas.new')}}"> --}}
      <a class="btn btn-primary pull-right" data-target='#modalInsertar' data-toggle='modal' id="btn-insertar">
        <i class="fa fa-plus"></i>Agregar Forma de Pago</a>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <input type="hidden" name="rol_user" value="{{auth()->user()->roles[0]->name}}">
        <table id="formas-pago-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">            
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
      formas_pago_table.ajax.url("{{route('formas_pago.getJson')}}").load();
    });

  </script>
  <script src="{{asset('js/formas_pago/index.js')}}"></script>
@endpush