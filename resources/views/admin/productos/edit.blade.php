@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          PRODUCTOS
          <small>Editar Producto</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('productos.index')}}"><i class="fa fa-list"></i> Productos</a></li>
          <li class="active">Actualizar</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="ProductoForm"  action="{{route('productos.update', $producto)}}">
            {{csrf_field()}}
            {{ method_field('PUT') }}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4 {{$errors->has('codigo')? 'has-error' : ''}}">
                                <label for="codigo">Codigo:</label>
                                <input type="text" class="form-control" placeholder="Codigo" name="codigo" value="{{old('codigo', $producto->codigo)}}">
                                {!!$errors->first('codigo', '<label class="error">:message</label>')!!}
                            </div>
                            <div class="col-sm-4">
                                <label for="nombre_comercial">Nombre Comercial:</label>
                                <input type="text" class="form-control" placeholder="Nombre Comercial" name="nombre_comercial" value="{{old('nombre_comercial', $producto->nombre_comercial)}}">
                            </div>
                            <div class="col-sm-4">
                                <label for="email">Nombre Genérico:</label>
                                <input type="text" class="form-control" placeholder="Nombre Genérico" name="nombre_generico" value="{{old('nombre_generico', $producto->nombre_generico)}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="concentracion">Concentración:</label>
                                <input type="text" class="form-control" placeholder="Concentración" name="concentracion" value="{{old('concentracion', $producto->concentracion)}}">
                            </div>
                            <div class="col-sm-4">
                                <label for="precio_venta">Precio de Venta:</label>
                                <input type="text" class="form-control" name="precio_venta" value="{{old('precio_Venta', $producto->precio_venta)}}">
                            </div> 
                            <div class="col-sm-4">
                                <label for="presentacion">Presentación:</label>
                                <select name="presentacion" class="form-control">
                                    @foreach ($presentaciones as $presentacion)
                                        @if ($presentacion->id == $producto->presentacion)
                                            <option value="{{$presentacion->id}}" selected >{{$presentacion->presentacion}}</option>
                                        @else
                                            <option value="{{$presentacion->id}}">{{$presentacion->presentacion}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="stock_maximo">Stock Máximo:</label>
                                <input type="text" class="form-control" placeholder="Stock máximo" name="stock_maximo" value="{{old('stock_maximo', $producto->stock_maximo)}}">
                            </div> 
                            <div class="col-sm-4">
                                <label for="stock_minimo">Stock Mínimo:</label>
                                <input type="text" class="form-control" placeholder="Stock mínimo" name="stock_minimo" value="{{old('stock_minimo', $producto->stock_minimo)}}">
                            </div> 
                        </div>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('productos.index') }}">Regresar</a>
                            <button class="btn btn-success form-button" id="ButtonProductoUpdate">Guardar</button>
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
<script src="{{asset('js/productos/edit.js')}}"></script>
@endpush