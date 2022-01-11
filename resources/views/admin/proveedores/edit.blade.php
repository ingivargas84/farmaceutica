@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          PROVEEDORES
          <small>Editar Proveedor</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('proveedores.index')}}"><i class="fa fa-list"></i> Proveedores</a></li>
          <li class="active">Crear</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="ProveedorUpdateForm"  action="{{route('proveedores.update', $proveedor)}}">
            {{csrf_field()}} {{ method_field('PUT') }}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4 {{$errors->has('nit')? 'has-error' : ''}}">
                                <label for="nit">Nit:</label>
                                <input type="text" class="form-control" placeholder="Nit:" name="nit" value="{{old('nit', $proveedor->nit)}}" >
                                {!!$errors->first('nit', '<label class="error">:message</label>')!!}
                            </div>
                            <div class="col-sm-4">
                                <label for="telefono">Teléfono:</label>
                                <input type="text" class="form-control" placeholder="Teléfono:" name="telefono" value="{{old('telefono', $proveedor->telefono)}}" >
                            </div>
                            <div class="col-sm-4">
                                <label for="celular">Email:</label>
                                <input type="text" class="form-control" placeholder="Email:" name="email" value="{{old('email', $proveedor->email)}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="nombre_legal">Nombre Legal:</label>
                                <input type="text" class="form-control" placeholder="Nombre Legal:" name="nombre_legal" value="{{old('nombre_legal', $proveedor->nombre_legal)}}">
                            </div>
                            <div class="col-sm-6">
                                <label for="nombre_comercial">Nombre Comercial:</label>
                                <input type="text" class="form-control" placeholder="Nombre Comercial:" name="nombre_comercial" value="{{old('nombre_comercial', $proveedor->nombre_comercial)}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-sm-3">
                              <label for="dias_credito">Días de Crédito:</label>
                              <input type="text" class="form-control" placeholder="Días de Crédito" name="dias_credito" value="{{old('dias_credito', $proveedor->dias_credito)}}">
                          </div>
                            <div class="col-sm-9">
                                <label for="direccion">Direcciónn:</label>
                                <input type="text" class="form-control" placeholder="Direcciónn:" name="direccion" value="{{old('direccion', $proveedor->direccion)}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="contacto1">Contacto 1:</label>
                                <input type="text" class="form-control" placeholder="Contacto 1:" name="contacto1" value="{{old('contacto1', $proveedor->contacto1)}}">
                            </div>
                            <div class="col-sm-6">
                                <label for="contacto2">contacto 2:</label>
                                <input type="text" class="form-control" placeholder="contacto 2:" name="contacto2" value="{{old('contacto2', $proveedor->contacto2)}}">
                            </div>
                        </div>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('proveedores.index') }}">Regresar</a>
                            <button class="btn btn-success form-button" id="ButtonProveedorUpdate">Guardar</button>
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

<script src="{{asset('js/proveedores/edit.js')}}"></script>
@endpush
