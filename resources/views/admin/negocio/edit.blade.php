@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          NEGOCIO
          <small>Editar Datos del Negocio</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="#"><i class="fa fa-list"></i> Negocio</a></li>
          <li class="active">Editar</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="NegocioUpdateForm"  action="{{route('negocio.update', $negocio)}}" enctype="multipart/form-data">
            {{csrf_field()}} {{ method_field('PUT') }}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4 {{$errors->has('nit')? 'has-error' : ''}}">
                                <label for="nit">Nit:</label>
                                <input type="text" class="form-control" placeholder="Nit" name="nit" value="{{old('nit', $negocio->nit)}}" >
                                {!!$errors->first('nit', '<label class="error">:message</label>')!!}
                            </div>

                            <div class="col-sm-4">
                                <label for="nombre_contable">Nombre Contable:</label>
                                <input type="text" class="form-control" placeholder="Nombre Contable" name="nombre_contable" value="{{old('nombre_contable', $negocio->nombre_contable)}}" >
                            </div>
                            <div class="col-sm-4">
                                <label for="nombre_comercial">Nombre Comercial:</label>
                                <input type="text" class="form-control" placeholder="Nombre Comercial" name="nombre_comercial" value="{{old('nombre_comercial', $negocio->nombre_comercial)}}" >
                            </div>
                        </div>
                        <br>                
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="direccion">Dirección:</label>
                                <input type="text" class="form-control" placeholder="Dirección" name="direccion" value="{{old('direccion', $negocio->direccion)}}">
                            </div>                                
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="telefonos">Teléfonos:</label>
                                <input type="text" class="form-control" placeholder="Teléfonos" name="telefonos" value="{{old('telefonos', $negocio->telefonos)}}">
                            </div>
                            <div class="col-sm-3">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" placeholder="Email" name="email" value="{{old('email', $negocio->email)}}">
                            </div>
                            <div class="col-sm-3">
                                <label for="no_patente">No. Patente:</label>
                                <input type="text" class="form-control" placeholder="No. Patente" name="no_patente" value="{{old('no_patente', $negocio->no_patente)}}">
                            </div>
                            <div class="col-sm-3">
                                <label>Fecha de Inicio de Operaciones:</label>
                        
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
    
                                    <input name="fecha_inicio" type="text" class="form-control pull-right" id="datepickerI" value="{{old('fecha_inicio', $negocio->fecha_inicio ? $negocio->fecha_inicio->format('d-m-Y') : null) }}">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12 {{$errors->has('logotipo')? 'has-error' : ''}}">
                                <label for="logotipo">Logotipo</label><br>
                                    @if($negocio->logotipo)
                                    <img width="120rem" src="{{$negocio->logotipo}}" alt="No tiene ningun logotipo">
                                    <br>
                                    @endif
                                <br>
                                <input type="file" name="logotipo">
                                {!!$errors->first('logotipo', '<label class="error">:message</label>')!!}
                            </div>
                        </div>

                        <br>
                        <div class="text-right m-t-15">
                            <button class="btn btn-success form-button">Guardar</button>
                        </div>
                                    
                    </div>
                </div>                
            </div>

            <div class="loader loader-bar is-active"></div>
    </form>

@stop


@push('styles')

@endpush


@push('scripts')

<script>
/*$('button').click(function(){
    $('.loader').addClass('is-active');
});*/

$(document).ready(function() {
        $('.loader').removeClass('is-active');
    });
</script>

<script src="{{asset('js/negocio/edit.js')}}"></script>
@endpush