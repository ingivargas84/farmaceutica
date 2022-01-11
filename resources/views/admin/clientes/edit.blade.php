@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          CLIENTES
          <small>Editar Cliente</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('clientes.index')}}"><i class="fa fa-list"></i> Clientes</a></li>
          <li class="active">Actualizar</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="ClienteForm"  action="{{route('clientes.update', $cliente)}}">
            {{csrf_field()}}
            {{ method_field('PUT') }}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="nombre_cliente">Nombre:</label>
                                <input type="text" class="form-control" placeholder="Nombre del cliente" name="nombre_cliente" value="{{old('nombre_cliente', $cliente->nombre_cliente)}}">
                            </div>
                            <div class="col-sm-4 {{$errors->has('nit')? 'has-error' : ''}}">
                                <label for="nit">Nit:</label>
                                <input type="text" class="form-control" placeholder="Nit:" name="nit" value="{{old('nit', $cliente->nit)}}">
                                {!!$errors->first('nit', '<label class="error">:message</label>')!!}
                            </div>
                            <div class="col-sm-4">
                                <label for="direccion">Dirección:</label>
                                <input type="text" class="form-control" placeholder="Dirección:" name="direccion" value="{{old('direccion', $cliente->direccion)}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="dias_credito">Días de crédito</label>
                                <input type="text" class="form-control" name="dias_credito" placeholder="Días de crédito" value="{{old('dias_credito', $cliente->dias_credito)}}">
                            </div>
                            <div class="col-sm-4">
                                <label for="territorio">Territorio</label>
                                <select name="territorio" class="form-control">
                                    @foreach ($territorios as $territorio)
                                        @if ($territorio->id == $cliente->territorio)
                                            <option value="{{$territorio->id}}" selected >{{$territorio->territorio}}</option>
                                        @else
                                            <option value="{{$territorio->id}}">{{$territorio->territorio}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="email">E-mail:</label>
                                <input type="text" class="form-control" placeholder="E-mail:" name="email" value="{{old('email', $cliente->email)}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="encargado_compras">Encargado de Compras (EC):</label>
                                <input type="text" class="form-control" placeholder="Nombre del encargado:" name="encargado_compras" value="{{old('encargado_compras', $cliente->encargado_compras)}}">
                            </div>

                            <div class="col-sm-4">
                                <label for="nacimiento_compras">Fecha de Nacimiento (EC):</label>

                                <div class="input-group date" data-provide="datepicker" id="nacimiento_ec">
                                    <input class="form-control" name="nacimiento_compras" value=" @php

                                    if($cliente->nacimiento_compras !== null){
                                        $date = date_create($cliente->nacimiento_compras);
                                        echo date_format($date, "m/d/Y");
                                    } else {
                                        echo ' ';
                                    }

                                    @endphp " id="nac_ec">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="col-sm-4">
                                <label for="telefono_compras">Teléfono (EC):</label>
                                <input type="text" class="form-control" placeholder="Teléfono:" name="telefono_compras" value="{{old('telefono_compras', $cliente->telefono_compras)}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="encargado_paga">Encargado de Paga (EP):</label>
                                <input type="text" class="form-control" placeholder="Nombre del encargado:" name="encargado_paga" value="{{old('encargado_paga', $cliente->encargado_paga)}}">
                            </div>

                            <div class="col-sm-4">
                                <label for="nacimiento_paga">Fecha de Nacimiento (EP):</label>

                                <div class="input-group date" data-provide="datepicker" id='nacimiento_ep'>
                                    <input class="form-control" name="nacimiento_paga" value="@php
                                    if($cliente->nacimiento_paga !== null){
                                        $date = date_create($cliente->nacimiento_paga);
                                        echo date_format($date, "m/d/Y");
                                    } else {
                                        echo " ";
                                    }
                                    @endphp" id="nac_ep">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="col-sm-4">
                                <label for="telefono_paga">Teléfono (EP):</label>
                                <input type="text" class="form-control" placeholder="Teléfono:" name="telefono_paga" value="{{old('telefono_paga', $cliente->telefono_paga)}}">
                            </div>
                        </div>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('clientes.index') }}">Regresar</a>
                            <button class="btn btn-success form-button" id="ButtonClienteUpdate">Guardar</button>
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
    $(document).ready(function(){
        $('#nacimiento_ec').datepicker({
            language: "es",
            todayHighlight: true,
            clearBtn: true,
            autoclose: true,
        });

        $('#nacimiento_ep').datepicker({
            language: "es",
            todayHighlight: true,
            clearBtn: true,
            autoclose: true,
        });
    });

    //thsi block of code adds or removes the date validation if the input has a value
    $('#nac_ec').on('change', function(){
        if ($('#nac_ec').val()) {
            $('#nac_ec').rules('add',{
                fecha: true,
            });
        }else{
            $('#nac_ec').rules('remove', 'fecha');
        }
    });
    $('#nac_ep').on('change', function(){
        if ($('#nac_ep').val()) {
            $('#nac_ep').rules('add',{
                fecha: true,
            });
        }else{
            $('#nac_ep').rules('remove', 'fecha');
        }
    });


</script>

<script src="{{asset('js/clientes/edit.js')}}"></script>
@endpush
