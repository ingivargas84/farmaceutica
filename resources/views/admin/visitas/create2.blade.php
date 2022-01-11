@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          Visitas
          <small>Crear Visita Cliente No Registrado</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('visitas.index')}}"><i class="fa fa-list"></i> Visitas</a></li>
          <li class="active">Crear</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="Visitas2Form"  action="{{route('visitas.save2')}}">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="nombre_cliente">Nombre Cliente:</label>
                                <input type="text" class="form-control" placeholder="Nombre Cliente:" name="nombre_cliente" >
                                <input type="hidden" name="cliente_id" id="cliente_id" value="0">
                            </div>
                        
                            <div class="col-sm-3">
                                <label for="telefono_cliente">Teléfono Cliente:</label>
                                <input type="text" class="form-control" placeholder="Teléfono Cliente:" name="telefono_cliente" >
                            </div>
                            </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-9">
                                <label for="direccion_cliente">Dirección Cliente:</label>
                                <input type="text" class="form-control" placeholder="Dirección Cliente:" name="direccion_cliente" >
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="observaciones">Observaciones:</label>
                                <input type="text" class="form-control" placeholder="Observaciones:" name="observaciones" >
                                <input type="hidden" name="fecha" value="<?php echo date("y-m-d");?>">
                            </div>
                        </div>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('visitas.index') }}">Regresar</a>
                            <button class="btn btn-success form-button" id="ButtonVisita2">Guardar</button>
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
<script src="{{asset('js/visitas/create.js')}}"></script>
@endpush
