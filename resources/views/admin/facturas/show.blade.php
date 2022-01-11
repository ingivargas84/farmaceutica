@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
        Ventas por Factura
        <small>Detalle del Factura No. {{$factura[0]->no_factura}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li><a href="{{route('facturas.index')}}"><i class="fa fa-list"></i> Ventas por Factura</a></li>
        <li class="active">Detalle</li>
    </ol>
</section>
@stop
@section('content')
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-2">
                    <h3><strong>Serie:</strong></h3>
                </div>
                <div class="col-sm-4">
                    <h3> {{$factura[0]->serie_factura}} </h3>
                </div>
                <div class="col-sm-2">
                    <h3><strong>No:</strong> </h3>
                </div>
                <div class="col-sm-4">
                    <h3>{{$factura[0]->no_factura}}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <h3><strong>Fecha:</strong> </h3>
                </div>
                <div class="col-sm-4">
                    <h3>{{$factura[0]->fecha}}</h3>
                </div>
                <div class="col-sm-2">
                    <h3><strong>Total:</strong> </h3>
                </div>
                <div class="col-sm-4">
                    <h3>Q. {{$factura[0]->total}}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <h3><strong>Subtotal:</strong> </h3>
                </div>
                <div class="col-sm-4">
                    <h3>Q. {{$factura[0]->subtotal}}</h3>
                </div>
                <div class="col-sm-2">
                    <h3><strong>Impuestos:</strong> </h3>
                </div>
                <div class="col-sm-4">
                    <h3>Q. {{$factura[0]->impuestos}}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <h3><strong>Cliente:</strong> </h3>
                </div>
                <div class="col-sm-4">
                    @if($factura[0]->cliente_factura)
                    <h3>{{$factura[0]->cliente_factura}}</h3>
                    <input type="hidden" id="urlActual" value="{{url()->current()}}">
                    @else
                    <h3>{{$factura[0]->cliente}}</h3>
                    <input type="hidden" id="urlActual" value="{{url()->current()}}">
                    @endif

                </div>
                <div class="col-sm-2">
                    <h3><strong>Estado:</strong> </h3>
                </div>
                <div class="col-sm-4">
                    @if ($factura[0]->estado == 1)
                        <h3>Creada</h3>
                    @else
                        <h3>Anulada</h3>
                    @endif
                </div>
            </div>
            @if ($factura[0]->estado != 1)
                <div class="row">
                    <div class="col-sm-4">
                        <h3><strong>Motivo de Anulación:</strong></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h4>
                            {{$factura[0]->motivo_anulacion}}
                        </h4>
                    </div>
                </div>
            @endif
            <div class="text-right m-t-15">
                <a class='btn btn-primary form-button' href="{{ route('facturas.index') }}">Regresar</a>
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
</script>
@endpush
