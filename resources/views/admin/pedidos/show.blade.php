@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
        PEDIDOS
        <small>Detalle del Pedido</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li><a href="{{route('pedidos.index')}}"><i class="fa fa-list"></i> Pedidos</a></li>
        <li class="active">Detalle</li>
    </ol>
</section>
@stop
@section('content')
@include('admin.pedidos.modalEditar')
<form method="POST" id="PedidoForm"  action="{{route('pedidos.save1')}}">
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-3">
                    <h3><strong>Fecha de ingreso:</strong></h3>
                </div>
                <div class="col-sm-3">
                    <h3> {{$pedido[0]->fecha}} </h3>
                </div>
                <div class="col-sm-3">
                    <h3><strong>Cliente:</strong> </h3>
                </div>
                <div class="col-sm-3">
                    <h3>{{$pedido[0]->cliente}}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <h3><strong>Vendedor:</strong> </h3>
                </div>
                <div class="col-sm-3">
                    <h3>{{$pedido[0]->vendedor}}</h3>
                </div>
                <div class="col-sm-3">
                    <h3><strong>Total:</strong> </h3>
                </div>
                <div class="col-sm-3">
                    <h3>Q.<?php  echo number_format($pedido[0]->total, 2, ".", ",") ?> </h3>
                    <input type="hidden" id="urlActual" value="{{url()->current()}}">
                </div>
            </div>
            @if ($pedido[0]->factura != null)
            <div class="row">
                <div class="col-sm-3">
                    <h3><strong>No. de Factura:</strong> </h3>
                </div>
                <div class="col-sm-3">
                    <h3>{{$pedido[0]->factura}}</h3>
                </div>
            </div>
            @endif
            <br>
            @if ($pedido[0]->factura == null  && $notas_envio[0]->id == null)
            <div class="row">
                <input type="hidden" name="id_pedido_maestro" id="id_pedido_maestro" idea value="{{$pedido[0]->id}}">
                  <input type="hidden" name="id_cliente" id="id_cliente" value="{{$pedido[0]->id_cliente}}">
                <div class="col-sm-2">
                    <label for="cliente_id">Nombre de Bodega:</label>
                    <select name="bodega" class="form-control" id="bodega" autofocus tabindex="1">
                        @foreach ($bodega as $b)
                            <option value="{{$b->id}}">{{$b->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="switch">¿Buscar por código?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-uno" name="sw" value="false" />
                        <label for="radio-uno">No</label>
                        <input type="radio" id="radio-dos" name="sw" value="true" checked />
                        <label for="radio-dos">Si</label>
                      </div>
                </div>
                <div class="col-sm-4">
                    <label for="codigo_producto">Buscar producto:</label>
                    <input type="text"  class="form-control" id="codigo-producto" name="codigo_producto" tabindex="2" placeholder="Código  del producto">
                    <input list="browsers" id="listaE" style="display:none" class="form-control" name="codigo_producto"  placeholder="Nombre  del producto">
                    <datalist id="browsers">
                      @foreach ($productos as $p)
                          <option value="{{$p->nombre_comercial}}">
                      @endforeach
                    </datalist>
                </div>
                <div class="col-sm-2">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" class="form-control" name="cantidad" placeholder="Cantidad del Producto" id="cantidad" tabindex="3">
                </div>
                <div class="col-sm-2">
                    <label for="stock">Existencias:</label>
                    <input type="number" class="form-control customreadonly" name="stock" placeholder="Existencias del producto en bodegas" id="stock">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-3">
                    <label for="nombre_comercial">Nombre Comercial:</label>
                    <input type="text" class="form-control customreadonly" name="nombre_comercial" id="nombre-com">
                    <input type="text" name="producto_id" readonly hidden id="producto-id">
                </div>
                <div class="col-sm-3">
                    <label for="presentacion">Presentación:</label>
                    <input type="text" class="form-control customreadonly" name="presentacion" id="presentacion">
                </div>
                <div class="col-sm-3">
                    <label for="precio">Precio:</label>
                    <div class="input-group">
                        <span class="input-group-addon">Q.</span>
                        <input type="number" class="form-control" placeholder="Precio unitario del producto" name="precio" id="precio">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="subtotal">Subtotal:</label>
                    <div class="input-group">
                        <span class="input-group-addon">Q.</span>
                        <input type="number" class="form-control customreadonly" placeholder="Subtotal" name="subtotal" id="subtotal">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
              <div class="col-sm-3">
                    <label for="switch">¿Registrar Factura?</label>
                    <div class="switch-field">
                      <input type="radio" id="radio-one" name="switch" value="false" checked/>
                      <label for="radio-one">No</label>
                      <input type="radio" id="radio-two" name="switch" value="true"  tabindex="4"/>
                      <label for="radio-two">Si</label>
                    </div>
              </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="text-left">
                        <h3 class="display-5"><strong>Detalle</strong></h3>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="text-right">
                        <button class="btn btn-success" id="agregar-detalle"
                            style="margin-top: 15px; margin-bottom: 10px">Agregar Detalle</button>
                        <input id="id_factura" type="hidden" value="{{$pedido[0]->id_factura}}">
                        <input type="hidden" id='fac_id'>
                        <input type="hidden" id='fac_fecha'>
                        <input type="hidden" id='fac_serie'>
                        <input type="hidden" id='fac_no'>
                        <input type="hidden" id='fac_sub'>
                        <input type="hidden" id='fac_tax'>
                        <input type="hidden" id='fac_total'>
                        <input type="hidden" id='fac_nombre'>
                        <input type="hidden" id='fac_cliente_id'>
                        <input type="hidden" id='fac_dias'>
                        <input type="hidden" id='fac_nit'>
                        <input type="hidden" id='fac_address'>
                        <input type="hidden" id='fac_tel'>
                        <input type="hidden" id='fac_seller'>
                    </div>
                </div>
                @endif
            </div>
            <table id="detalles-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"
                width="100%">
            </table>
            <br>
         <div class="row">
              <div class="col-sm-4">
                  <div class="input-group">
                      <input type="hidden" class="form-control customreadonly" placeholder="Total de la compra" name="total_ingreso" id="total" value="{{$pedido[0]->total}}">
                  </div>
                </div>
            </div>

        </div>
    </div>
</div>
</form>
<div class="loader loader-bar"></div>
&nbsp
@stop


@push('styles')
<style>
    .customreadonly{
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
        /* box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1); */
        transition: all 0.1s ease-in-out;
    }

    .switch-field label:hover {
        cursor: pointer;
    }

    .switch-field input:checked + label {
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
<script type="text/javascript">
  var a = 0;
</script>
@if ($pedido[0]->factura == null  && $notas_envio[0]->id == null)
<script type="text/javascript">
  var a = 1;
</script>
@endif
@if ($pedido[0]->factura != null)
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
                text: Object.byString(row, column),
                alignment: headers[i].alignmentChild
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
            headerRows: 1,
            widths: witdhsDef,
            body: buildTableBody(data, columns, showHeaders, headers)
        },
        layout: {
            fillColor: function(rowIndex, node, columnIndex) {
                return (rowIndex % 2 !== 0) ? '#fff' : null;
            },
            hLineWidth: function(i, node) {
                return (i === 0 || i === node.table.body.length) ? 0 : 1;
            },
            vLineWidth: function(i, node) {
                return 0;
            },
            hLineColor: function(i, node) {
                return 'white';
            },
        }
    };
}


//generate PDF report
$(document).on('click', '#print_receipt', function() {
        var id = $('#id_factura').val();
        var url = 'http://' + window.location.host + "/pedidos/getFactura/" + id;
        //get receipt header data and sets them to hidden inputs
        $.get(url, function(data) {
            $('#fac_id').val(data[0].id);
            $('#fac_fecha').val(data[0].fecha_factura);
            $('#fac_serie').val(data[0].serie_factura);
            $('#fac_no').val(data[0].no_factura);
            $('#fac_sub').val(data[0].subtotal);
            $('#fac_tax').val(data[0].impuestos);
            $('#fac_total').val(data[0].total);
            $('#fac_nombre').val(data[0].nombre_cliente);
            $('#fac_cliente_id').val(data[0].cliente_id);
            $('#fac_dias').val(data[0].dias_credito);
            $('#fac_nit').val(data[0].nit);
            $('#fac_address').val(data[0].direccion);
            $('#fac_tel').val(data[0].telefono_compras);
            $('#fac_seller').val(data[0].vendedor);
        });

        $.ajax({
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('#tokenReset').val()
            },
            url: "{{url()->current()}}" + "/getDetalles",
            dataType: "json",
            success: function(data) {
                var details = data['data'];
                details = JSON.stringify(details);
                details = JSON.parse(details);
                for (let i = 0; i < details.length; i++) {
                    details[i].precio   = "Q." + details[i].precio.toFixed(2);
                    details[i].subtotal = "Q." + details[i].subtotal.toFixed(2);
                }

                let encabezadoFactura = {
                    id:         $('#fac_id').val(),
                    fecha:      $('#fac_fecha').val(),
                    serie:      $('#fac_serie').val(),
                    no:         $('#fac_no').val(),
                    subtotal:   $('#fac_sub').val(),
                    impuesto:   $('#fac_tax').val(),
                    total:      $('#fac_total').val(),
                    nombre:     $('#fac_nombre').val(),
                    cliente_id: $('#fac_cliente_id').val(),
                    credito:    $('#fac_dias').val(),
                    nit:        $('#fac_nit').val(),
                    direccion:  $('#fac_address').val(),
                    telefono:   $('#fac_tel').val(),
                    vendedor:   $('#fac_seller').val(),
                }
                var dd = {
                    pageSize: {width: 8.5*72, height: 5.5*72},
                    pageOrientation: 'landscape',
                    pageMargins: [40, 40, 40, 50],
                    content: [
                        {text: "\n", lineHeight: 1.25},
                        {text: "\n", lineHeight: 1.25},
                        {text: "\n", lineHeight: 1.25},
                        {
                            columns: [
                                {
                                    width: '5%',
                                    text: ' '

                                },
                                {
                                    text: encabezadoFactura.fecha,
                                    style: 'data', alignment: 'left'
                                },
                                {
                                    text: encabezadoFactura.nombre.split(/\s/).reduce((response,word)=> response+=word.slice(0,1),'').toUpperCase() + "-" + encabezadoFactura.cliente_id,
                                    style:'data', alignment: 'right'
                                },
                                {
                                    text: encabezadoFactura.credito + " días",
                                    style: 'data', alignment: 'right'
                                },
                                {
                                    text: encabezadoFactura.nit,
                                    style: 'data', alignment: 'right'
                                },
                            ]
                        },
                        // { text: "\n" },
                        {
                            columns: [
                                {
                                    width: '5%',
                                    text: ' '
                                },
                                {
                                    width: '70%',
                                    text: encabezadoFactura.nombre,
                                    style: 'data', alignment: 'left'
                                },
                                {
                                    width: '25%',
                                    text: encabezadoFactura.vendedor,
                                    style: 'data', alignment: 'right'
                                },
                            ],
                        },
                        // { text: "\n" },
                        {
                            columns: [
                            {
                                width: '5%',
                                text: ' '
                            },
                            {
                                width: '70%',
                                text: encabezadoFactura.direccion,
                                style: 'data', alignment: 'left'
                            },
                            {
                                width: '25%',
                                text: encabezadoFactura.telefono, style: 'data', alignment: 'right'
                            },
                            ]
                        }, {
                            text: "\n", lineHeight: 0.8
                        },
                        table(
                            details,
                            ['codigo', 'cantidad', 'producto', 'precio', 'subtotal'],
                            ['10%', '10%', '60%', '10%', '10%'],
                            false,
                            [{
                                    text: 'Código',
                                    bold: 'true',
                                },
                                {
                                    text: 'Cantidad',
                                    bold: 'true',
                                },
                                {
                                    text: 'Producto',
                                    bold: 'true',
                                    //alignmentChild: 'center',
                                    //alignment: 'center'
                                },
                                {
                                    text: 'Precio',
                                    bold: 'true',
                                    alignmentChild: 'right',
                                    alignment: 'right'
                                },
                                {
                                    text: 'Subtotal',
                                    bold: 'true',
                                    alignmentChild: 'right',
                                    alignment: 'right'
                                }
                            ],
                            ''
                        ),
                    ],
                     footer: {
                        columns:[
                            {
                                width: '10%',
                                text: ''
                            },
                            {
                                width: '60%',
                                text: 'Test text',
                                alignment: 'right',
                            },
                            {
                                width: '25%',
                                text: 'Test text',
                                alignment: 'right',
                            },
                            {
                                width: '5%',
                                text: ''
                            }
                        ],
                        columns:[
                            {
                                width: '10%',
                                text: ''
                            },
                            {
                                width: '60%',
                                text: numeroALetras(encabezadoFactura.total),
                                alignment: 'right',
                            },
                            {
                                width: '25%',
                                text: 'Q. ' + encabezadoFactura.total,
                                alignment: 'right',
                            },
                            {
                                width: '5%',
                                text: ''
                            }
                        ],
                      },
                    styles: {
                        titulo: {
                            fontSize: 16,
                            bold: true
                        },
                        about: {
                            fontSize: 11,
                        },
                        subtitulo: {
                            fontSize: 11,
                            bold: true
                        },
                        data: {
                            fontSize: 11,
                            lineHeight: 1.5
                        },
                        fine: {
                            fontSize: 8,
                            bold: true,
                        }
                    }
                }

                pdfMake.createPdf(dd).print();

            },
            error: function() {

            }
        });

    })

    </script>
@endif
<script>
    $(document).ready(function () {
        $('.loader').fadeOut(225);
        detalles_table.ajax.url("{{url()->current()}}" + "/getDetalles").load();
    });

</script>


<script>
var datos  = [];
$(document).ready(function () {
    //change selectboxes to selectize mode to be searchable
    $("#clientes").select2();
  });
  //fix validation error message always displaying.
  $("#clientes").on("select2:close", function (e) {
      $(this).valid();
  });
  //gets the selected prduct data and sets the readonly inputs
  $(document).on('focusout', '#codigo-producto', function(){
      var codigo = $('#codigo-producto').val();
      var bodega = $('#bodega').val();
      var url = "@php echo url('/') @endphp" + "/pedidos/getProductoData/" + codigo + "/"+ bodega;
      $('#nombre-com-error').remove();
      $('#presentacion-error').remove();
      $('#nombre-com').val(null);
      $('#presentacion').val(null);
      $('#producto-id').val(null);
      $('#precio').val(null);
      $('#stock').val(null);
      $.ajax({
          url: url,
          success: function(data){
              if(data[0]){
                  $('#nombre-com').val(data[0].nombre_comercial);
                  $('#presentacion').val(data[0].presentacion);
                  $('#producto-id').val(data[0].id);
                  $('#precio').val(data[0].precio_venta);
                  $('#stock').val(data[0].existencias);
              } else {
                  alertify.set('notifier', 'position', 'top-center');
                  alertify.error  (
                      'No se encontraron existencias del producto en la bodega.');
                      $('#nombre-com').val(null);
                      $('#presentacion').val(null);
                      $('#producto-id').val(null);
              }
          },
          error: function(){
              alertify.set('notifier', 'position', 'top-center');
              alertify.error  (
                  'Hubo un error al buscar el producto. Asegúrese de que el código está correctamente escrito');
                  $('#nombre-com').val(null);
                  $('#presentacion').val(null);
                  $('#producto-id').val(null);
              }
          });
      });


      //gets the selected prduct data and sets the readonly inputs
      $(document).on('focusout', '#listaE', function(){
            var codigo = encodeURI($('#listaE').val());
          var bodega = $('#bodega').val();
          var url = "@php echo url('/') @endphp" + "/pedidos/getProductoData1/" + codigo + "/" + bodega;
          $('#nombre-com-error').remove();
          $('#presentacion-error').remove();
          $('#nombre-com').val(null);
          $('#presentacion').val(null);
          $('#producto-id').val(null);
          $('#precio').val(null);
          $('#stock').val(null);
          $.ajax({
              url: url,
              success: function(data){
                  if(data[0]){
                      $('#nombre-com').val(data[0].nombre_comercial);
                      $('#presentacion').val(data[0].presentacion);
                      $('#producto-id').val(data[0].id);
                      $('#precio').val(data[0].precio_venta);
                      $('#stock').val(data[0].existencias);
                  } else {
                      alertify.set('notifier', 'position', 'top-center');
                      alertify.error  (
                          'No se encontraron existencias del producto en la bodega ');
                          $('#nombre-com').val(null);
                          $('#presentacion').val(null);
                          $('#producto-id').val(null);
                  }
              },
              error: function(){
                  alertify.set('notifier', 'position', 'top-center');
                  alertify.error  (
                      'Hubo un error al buscar el producto. Asegúrese de que el código está correctamente escrito');
                      $('#nombre-com').val(null);
                      $('#presentacion').val(null);
                      $('#producto-id').val(null);
                  }
              });
          });


      //calculates subtotal desde cantidad
      $(document).on('focusout', '#cantidad', function(){
          $('#subtotal').val(null);
          let sub = parseFloat($('#precio').val()) * parseFloat($('#cantidad').val());
          sub = sub.toFixed(2);
          $('#subtotal').val(sub);
      });

      //calculates subtotal desde precio compra
      $(document).on('focusout', '#precio', function(){
          $('#subtotal').val(null);
          let sub = parseFloat($('#precio').val()) * parseFloat($('#cantidad').val());
          sub = sub.toFixed(2);
          $('#subtotal').val(sub);
      });

  //checks for empty fields
  function chkflds() {
      if ($('#nombre-com').val() && $('#cantidad').val() && $('#precio').val() && $('#subtotal').val() &&  $('#cantidad').valid()) {
          return true
      }else{
          return false
      }
  }

  $('#agregar-detalle').click(function(e){
      e.preventDefault();
      if(chkflds()){
          //adds the form data to the table
          detalles_table.row.add({
              'producto_id': $('#producto-id').val(),
              'producto': $('#nombre-com').val(),
              'cantidad': $('#cantidad').val(),
              'precio'  : $('#precio').val(),
              'subtotal': $('#subtotal').val(),
          }).draw();
          datos.push({
            'producto_id': $('#producto-id').val(),
            'producto': $('#nombre-com').val(),
            'cantidad': $('#cantidad').val(),
            'precio'  : $('#precio').val(),
            'subtotal': $('#subtotal').val(),
          });
          //adds all subtotal row data and sets the total input
              var total = 0;
              detalles_table.column(3).data().each(function(value, index){
              total = total + parseFloat(value);
              parseFloat(total);
              $('#total').val(total);
              $('#total-error').remove();
          });
          //resets form data
          $('#codigo-producto').val(null);
          $('#nombre-com').val(null);
          $('#cantidad').val(null);
          $('#presentacion').val(null);
          $('#precio').val(null);
          $('#subtotal').val(null);
          $('#stock').val(null);

          e.preventDefault();
                $('#cantidad').rules('remove','existencia');
          if ($('#PedidoForm').valid()) {
              var arr1 = $('#PedidoForm').serializeArray();
              var arr2 = datos;
              var arr3 = arr1.concat(arr2);

              $.ajax({
                  type: 'POST',
                  url: "{{route('pedidos.save1')}}",
                  headers:{'X-CSRF-TOKEN': $('#tokenReset').val(),},
                  data: JSON.stringify(arr3),
                  dataType: 'json',
                  success: function(){
                      $('#clientes').val('default');
                      $('#serie-factura').val(null);
                      $('#total').val(null);
                      detalles_table.rows().remove().draw();
                      window.location.assign('{{url()->current()}}?ajaxSuccess')
                  },
                  error: function(){
                      alertify.set('notifier', 'position', 'top-center');
                      alertify.error('Hubo un error al registrar la compra')
                  }
              })
          }
      }else{
          alertify.set('notifier', 'position', 'top-center');
          alertify.error  ('Debe seleccionar un producto y una cantidad menor o igual a las existencias en bodega')
      }
  });

  $(document).one('click', '#ButtonPedido', function(e){
      e.preventDefault();
            $('#cantidad').rules('remove','existencia');
      if ($('#PedidoForm').valid()) {
          var arr1 = $('#PedidoForm').serializeArray();
          var arr2 = datos;
          var arr3 = arr1.concat(arr2);

          $.ajax({
              type: 'POST',
              url: "{{route('pedidos.save1')}}",
              headers:{'X-CSRF-TOKEN': $('#tokenReset').val(),},
              data: JSON.stringify(arr3),
              dataType: 'json',
              success: function(){
                  $('#clientes').val('default');
                  $('#serie-factura').val(null);
                  $('#total').val(null);
                  detalles_table.rows().remove().draw();
                  window.location.assign('/pedidos?ajaxSuccess')
              },
              error: function(){
                  alertify.set('notifier', 'position', 'top-center');
                  alertify.error('Hubo un error al registrar la compra')
              }
          })
      }
  });

  $(document).on('click', '#radio-two', function(){
      if(this.checked == true){
          $('#serie').removeClass('customreadonly');
          $('#serie').prop('maxlength', 256);
          $('#serie').rules('add', {
              required: true,
                  messages:{
                      required: 'Este campo es obligatorio'
              }
          });
          $('#no-factura').removeClass('customreadonly');
          $('#no-factura').prop('maxlength', 256);
          $('#no-factura').rules('add', {
              required: true,
                  messages:{
                      required: 'Este campo es obligatorio'
              }
          });
      }
  });
  $(document).on('click', '#radio-one', function(){
      if(this.checked == true){
          $('#serie').addClass('customreadonly');
          $('#serie').prop('maxlength', 0);
          $('#serie').rules('remove','required');
          $('#no-factura').addClass('customreadonly');
          $('#no-factura').prop('maxlength', 0);
          $('#no-factura').rules('remove','required');
      }
  });

  $(document).on('click', '#radio-uno', function(){
      if(this.checked == true){
        $('#codigo-producto').css('display', 'none');
        $('#listaE').css('display', 'inline');

          //$('#codigo-producto').type('text');
          //$('#lista').type('hidden');


      }
  });
  $(document).on('click', '#radio-dos', function(){
      if(this.checked == true){

        $('#codigo-producto').css('display', 'inline');
        $('#listaE').css('display', 'none');
      //  $('#codigo-producto').type('hidden');
        //$('#lista').type('text');

      }
  });
</script>
<script src="{{asset('js/pedidos/show.js')}}"></script>
<script src="{{asset('js/pedidos/numero_letras.js')}}"></script>
<script src="{{asset('js/pedidos/create.js')}}"></script>{{-- validator --}}
@endpush
