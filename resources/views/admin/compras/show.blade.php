@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          COMPRAS
          <small>Detalle de Compra</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('compras.index')}}"><i class="fa fa-list"></i> Compras</a></li>
          <li class="active">Detalle</li>
        </ol>
    </section>
@stop
@section('content')
@include('admin.users.confirmarAccionModal')
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h3><strong>Fecha de la Compra:</strong> {{$compra[0]->fecha_compra}} </h3>
                            </div>
                            <div class="col-sm-4">
                                <h3><strong>Fecha de la Factura:</strong> {{$compra[0]->fecha_factura}} </h3>
                            </div>
                            <div class="col-sm-4">
                                <h3><strong>Serie de la Factura:</strong> {{$compra[0]->serie_factura}} </h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <h3><strong>No. de Factura:</strong> {{$compra[0]->num_factura}} </h3>
                            </div>
                            <div class="col-sm-4">
                                <h3><strong>Usuario:</strong> {{$compra[0]->user}} </h3>
                            </div>
                            <div class="col-sm-4">
                                <h3><strong>Proveedor:</strong> {{$compra[0]->proveedor}} </h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <h3><strong>Bodega:</strong> {{$compra[0]->bodega}} </h3>
                            </div>
                            <div class="col-sm-4">
                                <h3><strong>Total:</strong> Q. {{$compra[0]->total_ingreso}} </h3>
                            </div>
                        </div>
                        <br>
                        <h3 class="display-5"><strong>Detalle</strong></h3>
                        <table id="detalles-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">
                        </table>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('compras.index') }}">Regresar</a>
                        </div>
                                    
                    </div>
                </div>                
            </div>
    <div class="loader loader-bar"></div>

@stop


@push('styles')

@endpush


@push('scripts')
  <script>
    $(document).ready(function() {
      $('.loader').fadeOut(225);
      detalles_table.ajax.url("{{url()->current()}}" + "/getDetalles").load();
    });
  </script>
  <script src="{{asset('js/compras/show.js')}}"></script>
@endpush