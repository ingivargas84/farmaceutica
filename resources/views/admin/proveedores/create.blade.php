@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          PROVEEDORES
          <small>Crear Proveedor</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('proveedores.index')}}"><i class="fa fa-list"></i> Proveedores</a></li>
          <li class="active">Crear</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="ProveedorForm"  action="{{route('proveedores.save')}}">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="nit">Nit:</label>
                                <input type="text" class="form-control" placeholder="Nit:" name="nit" >
                            </div>
                            <div class="col-sm-4">
                                <label for="telefono">Teléfono:</label>
                                <input type="text" class="form-control" placeholder="Teléfono:" name="telefono" >
                            </div>
                            <div class="col-sm-4">
                                <label for="celular">Email:</label>
                                <input type="text" class="form-control" placeholder="Email:" name="email" >
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="nombre_legal">Nombre Legal:</label>
                                <input type="text" class="form-control" placeholder="Nombre Legal:" name="nombre_legal" >
                            </div>
                            <div class="col-sm-6">
                                <label for="nombre_comercial">Nombre Comercial:</label>
                                <input type="text" class="form-control" placeholder="Nombre Comercial:" name="nombre_comercial" >
                            </div>
                        </div>
                        <br>

                        <div class="row">
                          <div class="col-sm-3">
                              <label for="dias_credito">Días de Crédito:</label>
                              <input type="text" class="form-control" placeholder="Días de Crédito" name="dias_credito" >
                          </div>
                            <div class="col-sm-9">
                                <label for="direccion">Dirección:</label>
                                <input type="text" class="form-control" placeholder="Dirección:" name="direccion" >
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="contacto1">Contacto 1:</label>
                                <input type="text" class="form-control" placeholder="Contacto 1:" name="contacto1" >
                            </div>
                            <div class="col-sm-6">
                                <label for="contacto2">Contacto 2:</label>
                                <input type="text" class="form-control" placeholder="Contacto 2:" name="contacto2" >
                            </div>
                        </div>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('proveedores.index') }}">Regresar</a>
                            <button class="btn btn-success form-button" id="ButtonProveedor">Guardar</button>
                        </div>

                    </div>
                </div>
            </div>
    </form>
    <div class="loader loader-bar"></div>

@stop


@push('styles')

@endpush


@push('scripts')

<script>
    $.validator.addMethod("nitUnico", function(value, element) {
        var valid = false;
        $.ajax({
            type: "GET",
            async: false,
            url: "{{route('proveedores.nitDisponible')}}",
            data: "nit=" + value,
            dataType: "json",
            success: function(msg) {
                valid = !msg;
            }
        });
        return valid;
    }, "El nit ya está registrado en el sistema");
</script>

<script src="{{asset('js/proveedores/create.js')}}"></script>
@endpush
