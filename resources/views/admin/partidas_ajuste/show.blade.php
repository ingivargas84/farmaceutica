@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
        Partidas Ajuste
        <small>Detalle de la Partida</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li><a href="{{route('partidas_ajuste.index')}}"><i class="fa fa-list"></i> Partidas de Ajuste</a></li>
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
                    <h3><strong>Código:</strong> </h3>
                </div>
                <div class="col-sm-2">
                    <h3>{{$partidaAjuste->codigo_partida}}</h3>
                </div>
                <div class="col-sm-2">
                    <h3><strong>Fecha:</strong></h3>
                </div>
                <div class="col-sm-2">
                    <h3> {{$partidaAjuste->fecha_ingreso}} </h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <h3><strong>Ingreso:</strong> </h3>
                </div>
                <div class="col-sm-2">
                    <h3>Q. {{$partidaAjuste->total_ingreso}}</h3>
                </div>
                <div class="col-sm-2">
                    <h3><strong>Salida:</strong> </h3>
                </div>
                <div class="col-sm-2">
                    <h3>Q. {{$partidaAjuste->total_salida}}</h3>
                    <input type="hidden" id="urlActual" value="{{url()->current()}}">
                </div>
                <div class="col-sm-2">
                    <h3><strong>Saldo:</strong> </h3>
                </div>
                <div class="col-sm-2">
                    <h3>Q. {{$partidaAjuste->saldo}}</h3>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-6">
                    <div class="text-left">
                        <h3 class="display-5"><strong>Detalle</strong></h3>
                    </div>
                </div>
            </div>
            <table id="detalles-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap" width="100%">
            </table>
            <br>
            <div class="text-right m-t-15">
                <a class='btn btn-primary form-button' href="{{ route('partidas_ajuste.index') }}">Regresar</a>
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
    // Get property value by key/nested key path
    Object.byString = function(o, s) {
        s = s.replace(/\[(\w+)\]/g, '.$1'); // convert indexes to properties
        s = s.replace(/^\./, ''); // strip a leading dot
        var a = s.split('.');
        for (var i = 0, n = a.length; i < n; ++i) {
            var k = a[i];
            if (k in o) {
                o = o[k];
            } else {
                return;
            }
        }
        return o;
    }

    // Table body builder
    function buildTableBody(data, columns, showHeaders, headers) {
        var body = [];
        // Inserting headers
        if (showHeaders) {
            body.push(headers);
        }

        // Inserting items from external data array
        data.forEach(function(row) {
            var dataRow = [];
            var i = 0;

            columns.forEach(function(column) {
                dataRow.push({
                    text: Object.byString(row, column)
                    , alignment: headers[i].alignmentChild
                });
                i++;
            })
            body.push(dataRow);

        });

        return body;
    }

    // returns a pdfmake table
    function table(data, columns, witdhsDef, showHeaders, headers, layoutDef) {
        return {
            table: {
                headerRows: 1
                , widths: witdhsDef
                , body: buildTableBody(data, columns, showHeaders, headers)
            }
            , layout: {
                fillColor: function(rowIndex, node, columnIndex) {
                    return (rowIndex % 2 !== 0) ? '#fff' : null;
                }
                , hLineWidth: function(i, node) {
                    return (i === 0 || i === node.table.body.length) ? 0 : 1;
                }
                , vLineWidth: function(i, node) {
                    return 0;
                }
                , hLineColor: function(i, node) {
                    return 'white';
                }
            , }
        };
    }

</script>
<script>
    $(document).ready(function() {
        $('.loader').fadeOut(225);
        detalles_table.ajax.url("{{url()->current()}}" + "/getDetalles").load();
    });

</script>
<script src="{{asset('js/partidas_ajuste/show.js')}}"></script>
@endpush
