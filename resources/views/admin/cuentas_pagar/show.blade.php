@extends('admin.layoutadmin')

@section('header')
    <section class="content-header">
        <h1>
          CUENTAS POR PAGAR
          <small>Detalle de la Cuenta por Pagar</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
          <li><a href="{{route('cuentas_pagar.index')}}"><i class="fa fa-list"></i> Cuentas por Pagar</a></li>
          <li class="active">Detalle</li>
        </ol>
    </section>
@stop
@section('content')
@include('admin.users.confirmarAccionModal')
@include('admin.cuentas_pagar.modalAbono')
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h3><strong>Creación:</strong> {{$cuenta[0]->fecha}} </h3>
                            </div>
                            <div class="col-sm-4">
                                <h3><strong>Proveedor:</strong> {{$cuenta[0]->proveedor}} </h3>
                            </div>
                            <div class="col-sm-4">
                                <h3><strong>Saldo:</strong>Q. <span id='saldo'>{{$cuenta[0]->saldo}}</span></h3>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-left">
                                    <h3 class="display-5"><strong>Detalle</strong></h3>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-right">
                                    <button  data-target='#modalAbono' data-toggle='modal' id="print_receipt" class="btn btn-success"
                                        style="margin-top: 15px; margin-bottom: 10px">Registrar Abono</button>
                                </div>
                            </div>
                        </div>
                        <table id="detalles-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">
                        </table>
                        <br>
                        <div class="text-right m-t-15">
                            <a class='btn btn-primary form-button' href="{{ route('cuentas_pagar.index') }}">Regresar</a>
                        </div>
                                    
                    </div>
                </div>                
            </div>
        <input type="hidden" name="urlActual" value="{{url()->current()}}"> 
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
  <script src="{{asset('js/cuentas_pagar/show.js')}}"></script>
@endpush