@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          PRODUCTOS
          <small>Crear Producto</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('productos.index')}}"><i class="fa fa-list"></i> Productos</a></li>
          <li class="active">Crear</li>
        </ol>
    </section>
@stop

@section('content')
    <form method="POST" id="ProductoForm"  action="{{route('productos.save')}}">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="codigo">Código:</label>
                                <input type="text" class="form-control" placeholder="Código del producto" name="codigo" >
                            </div>
                            <div class="col-sm-4">
                                <label for="nombre_comercial">Nombre Comercial:</label>
                                <input type="text" class="form-control" placeholder="Nombre comercial" name="nombre_comercial" >
                            </div>
                            <div class="col-sm-4">
                                <label for="nombre_generico">Nombre Genérico:</label>
                                <input type="text" class="form-control" placeholder="Nombre genérico" name="nombre_generico" >
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="concentracion">Concentración:</label>
                                <input type="text" class="form-control" name="concentracion" placeholder="Concentración">
                            </div>
                            <div class="col-sm-4">
                                <label for="precio_venta">Precio de Venta:</label>
                                <input type="number" class="form-control" placeholder="Precio de venta" name="precio_venta" >
                            </div>
                            <div class="col-sm-4">
                                <label for="presentacion">Presentación:</label>
                                <select name="presentacion" class="form-control">
                                    @foreach ($presentaciones as $presentacion)
                                        <option value="{{$presentacion->id}}">{{$presentacion->presentacion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="stock_maximo">Stock Máximo:</label>
                                <input type="number" class="form-control" placeholder="Stock máximo" name="stock_maximo" >
                            </div>
                            <div class="col-sm-6">
                                <label for="stock_minimo">Stock Mínimo:</label>
                                <input type="number" class="form-control" placeholder="Stock mínimo" name="stock_minimo" >
                            </div>
                        </div>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('productos.index') }}">Regresar</a>
                            <button id="ButtonProducto" class="btn btn-success form-button">Guardar</button>
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
<script src="{{asset('js/productos/create.js')}}"></script>
<script>
        $.validator.addMethod("codigoUnico", function(value, element) {
        var valid = false;
        $.ajax({
            type: "GET",
            async: false,
            url: "{{route('productos.codigoDisponible')}}",
            data: "codigo=" + value,
            dataType: "json",
            success: function(msg) {
                valid = !msg;
            }
        });
        return valid;
    }, "El código ya está registrado en el sistema");
</script>
@endpush