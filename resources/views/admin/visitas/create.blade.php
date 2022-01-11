@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          Visitas
          <small>Crear Visita</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('visitas.index')}}"><i class="fa fa-list"></i> Visitas</a></li>
          <li class="active">Crear</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="VisitasForm"  action="{{route('visitas.save')}}">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="cliente_id">Cliente:</label>
                                <input list="browsers" id="lista" class="form-control" name="cliente"  placeholder="Nombre del cliente">
                                <datalist id="browsers">
                                  @foreach ($clientes as $cl)
                                     <option data-value="{{$cl->id}}" value="{{$cl->nombre_cliente}}"></option>
                                  @endforeach
                                </datalist>
                                <input type="hidden" name="cliente_id" id="cliente_id" value="">
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
                            <button class="btn btn-success form-button" id="ButtonVisita">Guardar</button>
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
<script type="text/javascript">
$(document).on('focusout', '#lista', function(){
  var shownVal= document.getElementById("lista").value;
  var value2send=document.querySelector("#browsers option[value='"+shownVal+"']").dataset.value;
    console.log(value2send);
        $('#cliente_id').val(value2send);
    });
</script>
<script src="{{asset('js/visitas/create.js')}}"></script>
@endpush
