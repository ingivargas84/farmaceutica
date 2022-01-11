@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
        Traspasos de Bodega
        <small>Crear Traspaso</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li><a href="{{route('traspasos_bodega.index')}}"><i class="fa fa-list"></i> Traspasos de Bodega</a></li>
        <li class="active">Crear</li>
    </ol>
</section>
@stop

@section('content')
<form method="POST" id="TraspasoForm" action="{{route('traspasos_bodega.save')}}" autocomplete="off">
    {{csrf_field()}}
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <label for="bodega_origen">Bodega de Origen:</label>
                        <select name="bodega_origen" id="b-1" class="form-control" autofocus tabindex="1">
                            <option value="default">Seleccione la bodega de origen</option>
                            @foreach ($bodegas as $bodega)
                            <option value="{{$bodega->id}}">{{$bodega->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="bodega_destino">Bodega de Destino:</label>
                        <select name="bodega_destino" id="b-2" class="form-control" tabindex="2">
                            <option value="default">Seleccione la bodega de destino</option>
                            @foreach ($bodegas as $bodega)
                            <option value="{{$bodega->id}}">{{$bodega->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <label for="switch">Buscar producto por:</label>
                        <div class="switch-field">
                            <input type="radio" id="radio-one" name="switch" value=false checked />
                            <label for="radio-one">Código</label>
                            <input type="radio" id="radio-two" name="switch" value=true />
                            <label for="radio-two">Nombre</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="producto">Buscar producto:</label>
                        <input type="text" class="form-control" name="producto" placeholder="Código del Producto" tabindex="3" id="producto">
                        <input list="browsers" id="lista" style="display:none" class="form-control" name="codigo_producto" tabindex="3" placeholder="Nombre  del producto">
                        <datalist id="browsers">
                            @foreach ($productos as $p)
                            <option value="{{$p->nombre_comercial}}">
                                @endforeach
                        </datalist>
                        <input type="hidden" name="producto_id" value="" id="producto-id">
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" class="form-control" placeholder="Cantidad de producto a transferir" id="cantidad" name="cantidad" tabindex="4">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <label for="nombre_prod">Nombre del Producto:</label>
                        <input type="text" class="form-control" placeholder="Nombre comercial del producto" name="nombre_prod" id="nombre-prod" readonly>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="stock">Existencias en bodega de origen:</label>
                        <input type="number" class="form-control" placeholder="Existencias del producto" name="stock" id="existencias" readonly>
                    </div>
                </div>
                <br>
                <div class="text-right m-t-15">
                    <a class='btn btn-primary form-button' href="{{ route('traspasos_bodega.index') }}">Regresar</a>
                    <button id="ButtonTraspaso" class="btn btn-success form-button" tabindex="5">Guardar</button>
                </div>

            </div>
        </div>
    </div>
</form>
<div class="loader loader-bar"></div>

@stop


@push('styles')
<style>
    div.col-md-6 {
        margin-bottom: 15px;
    }

    .customreadonly {
        background-color: #eee;
        cursor: not-allowed;
        pointer-events: none;
    }

    .switch-field {
        display: flex;
        margin-bottom: 36px;
        overflow: hidden;
    }

    .switch-field input {
        position: absolute !important;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        width: 1px;
        border: 0;
        overflow: hidden;
    }

    .switch-field label {
        background-color: #e4e4e4;
        color: rgba(0, 0, 0, 0.6);
        font-size: 14px;
        line-height: 1;
        text-align: center;
        padding: 8px 16px;
        margin-right: -1px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
        transition: all 0.1s ease-in-out;
        width: 50%;
    }

    .switch-field label:hover {
        cursor: pointer;
    }

    .switch-field input:checked+label {
        background-color: #55bd8c;
        box-shadow: none;
    }

    .switch-field label:first-of-type {
        border-radius: 4px 0 0 4px;
    }

    .switch-field label:last-of-type {
        border-radius: 0 4px 4px 0;
    }

</style>


@endpush


@push('scripts')
<script defer src="{{asset('js/traspasos_bodega/create.js')}}"></script>
<script defer>
    $('#b-1').on('change', function() {
        if ($('#radio-one').prop('checked')) {
            if ($('#producto').val() != "") {
                getExistencias('codigo');
            }
        } else if ($('#radio-two').prop('checked')) {
            if ($('#lista').val() != "") {
                getExistencias('nombre');
            }
        }
    });

    $('#producto').focusout(function() {
        if ($('#b-1').val() !== 'default') {
            getExistencias('codigo');
        }
    });

    $('#lista').focusout(function() {
        if ($('#b-1').val() !== 'default') {
            getExistencias('nombre');
        }
    });

    //gets the selected prduct data and sets the readonly inputs
    function getExistencias(tipo) {
        //set-up variables
        var bodega = $('#b-1').val();
        var codigo;
        if (tipo == 'codigo') {
            codigo = $('#producto').val();
        } else if (tipo == 'nombre') {
            codigo = encodeURI($('#lista').val());
        }

        var url;

        if (tipo == 'codigo') {
            var url = "@php echo url('/') @endphp" + "/traspasos_bodega/getProducto/" + codigo + "/" + bodega;
        } else if (tipo == 'nombre') {
            var url = "@php echo url('/') @endphp" + "/traspasos_bodega/getProductoNombre/" + codigo + "/" + bodega;
        }

        //reset form controls
        $('#nombre-prod-error').remove();
        $('#existencias-error').remove();
        $('#nombre-prod').val(null);
        $('#existencias').val(null);
        $('#producto-id').val(null);
        //get data over ajax
        $.ajax({
            url: url
            , success: function(data) {
                $('#nombre-prod').val(data[0].nombre_comercial);
                $('#existencias').val(data[0].existencias);
                $('#producto-id').val(data[0].id);
            }
            , error: function() {
                alertify.set('notifier', 'position', 'top-center');
                alertify.error(
                    'No se encontró el producto. Por favor, vuelva a escribir el código');
                $('#nombre-prod').val(null);
                $('#existencias').val(null);
                $('#producto-id').val(null);
            }
        });
    };


    $(document).on('click', '#radio-two', function() {
        if (this.checked == true) {
            $('#producto').css('display', 'none');
            $('#lista').css('display', 'inline');
        }
    });

    $(document).on('click', '#radio-one', function() {
        if (this.checked == true) {

            $('#producto').css('display', 'inline');
            $('#lista').css('display', 'none');
        }
    });


    //asks for user confirmation on submit
    $(document).on('click', '#ButtonTraspaso', function(e) {
        e.preventDefault();
        alertify.confirm('Realizar traspaso', "¿Está seguro que desea realizar el traspaso? \n Esta operación es irreversible"
            , function() {
                $('#TraspasoForm').submit();
            }
            , function() {
                alertify.set('notifier', 'position', 'top-center');
                alertify.error('Operación cancelada');
            })
    })

</script>
@endpush
