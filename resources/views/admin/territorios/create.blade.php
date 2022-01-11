@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          Territorios
          <small>Crear Territorio</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('territorios.index')}}"><i class="fa fa-list"></i> Territorios</a></li>
          <li class="active">Crear</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="TerritorioForm"  action="{{route('territorios.save')}}">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="territorio">Territorio:</label>
                                <input type="text" class="form-control" placeholder="Territorio:" name="territorio" >
                            </div>
                            <div class="col-sm-6">
                                <label for="Vendedor">Selecciones Vendedor:</label>
                                <select name="user" class="form-control">
                                    <option value="0">------------------------</option>
                                    @foreach ($usuario as $u)
                                        <option value="{{$u->id}}">{{$u->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="descripcion">Descripcion:</label>
                                <input type="text" class="form-control" placeholder="Descripcion:" name="descripcion" >
                            </div>
                        </div>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('territorios.index') }}">Regresar</a>
                            <button class="btn btn-success form-button">Guardar</button>
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
    $.validator.addMethod("territorioUnico", function(value, element) {
        var valid = false;
        $.ajax({
            type: "GET",
            async: false,
            url: "{{route('territorios.territorioDisponible')}}",
            data: "territorio=" + value,
            dataType: "json",
            success: function(msg) {
                valid = !msg;
            }
        });
        return valid;
    }, "El territorio ya está registrado en el sistema");
</script>

<script src="{{asset('js/territorios/create.js')}}"></script>
@endpush
