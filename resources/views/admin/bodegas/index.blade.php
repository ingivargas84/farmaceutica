@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
      Listado de Bodegas
      <small>Todos los bodegas</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active">Bodegas</li>
    </ol>
  </section>

  @endsection

@section('content')
@include('admin.bodegas.modalEditar')
@include('admin.bodegas.modalInsertar')
@include('admin.users.confirmarAccionModal')
<div class="loader loader-bar is-active"></div>
<div class="box">
  <div class="box-header">
    {{-- <a class="btn btn-primary pull-right" href="{{route('bodegas.new')}}"> --}}
      <a class="btn btn-primary pull-right" data-target='#modalInsertar' data-toggle='modal'>
        <i class="fa fa-plus"></i>Agregar Bodega</a>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <input type="hidden" name="rol_user" value="{{auth()->user()->roles[0]->name}}">
        <table id="bodegas-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">            
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
      bodegas_table.ajax.url("{{route('bodegas.getJson')}}").load();
    });

  </script>
  <script src="{{asset('js/bodegas/index.js')}}"></script>
@endpush