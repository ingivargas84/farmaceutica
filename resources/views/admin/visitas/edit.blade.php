@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          Visitas
          <small>Editar Visita</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('visitas.index')}}"><i class="fa fa-list"></i> Visitas</a></li>
          <li class="active">Actualizar</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="VisitaEditForm"  action="{{route('visitas.update', $visita)}}">
            {{csrf_field()}}
            {{ method_field('PUT') }}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="cliente_id">Cliente:</label>
                                <select name="cliente_id" class="form-control">
                                    @foreach ($clientes as $cl)
                                        @if ($cl->id == $visita->cliente_id)
                                            <option value="{{$cl->id}}" selected >{{$cl->nombre_cliente}}</option>
                                        @else
                                            <option value="{{$cl->id}}">{{$cl->nombre_cliente}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="observaciones">Observaciones:</label>
                                <input type="text" class="form-control" name="observaciones" value="{{old('observaciones', $visita->observaciones)}}">
                            </div>
                        </div>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('visitas.index') }}">Regresar</a>
                            <button class="btn btn-success form-button" id="ButtonVisitasUpdate">Guardar</button>
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
<script src="{{asset('js/visitas/edit.js')}}"></script>
@endpush