@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
      Listado de Territorios
      <small>Todos los territorios</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active">Territorios</li>
    </ol>
  </section>

  @endsection

@section('content')
@include('admin.users.confirmarAccionModal')
@include('admin.territorios.modalEditar')

<div class="loader loader-bar is-active"></div>
<div class="box">
    <div class="box-header">
      <a class="btn btn-primary pull-right" href="{{route('territorios.new')}}">
        <i class="fa fa-plus"></i>Agregar Territorio</a>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <input type="hidden" name="rol_user" value="{{auth()->user()->roles[0]->name}}">
        <table id="territorios-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">
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
    $.validator.addMethod("territorioUnicoEditar", function(value, element) {
    var valid = false;
    var id = $("#id_edit").val();
    $.ajax({
        type: "GET",
        async: false,
        url: "{{route('territorios.territorioDisponibleEditar')}}",
        data: { "territorio": value, "id": id },
        dataType: "json",
        success: function(msg) {
            valid = !msg;
        }
    });
    return valid;
}, "El territorio ya está registrado en el sistema");


    $(document).ready(function() {
      $('.loader').fadeOut(225);
      territorios_table.ajax.url("{{route('territorios.getJson')}}").load();
    });

  </script>
  <script src="{{asset('js/territorios/index.js')}}"></script>
@endpush
