@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
      Registro de Visitas
      <small>Todas las visitas</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active">Visitas</li>
    </ol>
  </section>

  @endsection

@section('content')
@include('admin.users.confirmarAccionModal')

<div class="loader loader-bar is-active"></div>
<div class="box">
    <div class="box-header">
      <button class="btn btn-primary pull-left" data-toggle="modal" data-target="#modal_visitas">
      <i class="fa fa-print"></i> Generar Reporte de Visitas</button>
      <a class="btn btn-success pull-right" href="{{route('visitas.new')}}">
        <i class="fa fa-plus"></i>Visita Cliente Registrado</a>
      
      <a class="btn btn-primary pull-right" href="{{route('visitas.new2')}}">
        <i class="fa fa-plus"></i>Visita Cliente No Registrado</a>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <input type="hidden" name="rol_user" value="{{auth()->user()->roles[0]->name}}">
        <table id="visitas-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">
        </table>
        <input type="hidden" name="urlActual" value="{{url()->current()}}">
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->

  <form action="{{route('reportes.visitas')}}" id="VisitasForm"  method="post" >

  {{csrf_field()}}

  @include('admin.reportes.visitas')

</form>
@endsection


@push('styles')


@endpush

@push('scripts')
  <script>

    $(document).ready(function() {
      $('.loader').fadeOut(225);
      visitas_table.ajax.url("{{route('visitas.getJson')}}").load();
    });

  </script>
  <script src="{{asset('js/visitas/index.js')}}"></script>
@endpush
