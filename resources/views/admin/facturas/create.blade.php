<div class="modal fade" id="modalFactura" tabindex="-1" role="dialog">
    <form method="POST" id="InsertarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Crear Nueva Factura</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                  <div class="col-sm-6">
                      <label for="">Seleccione Pedido a Facturar</label>
                      <input name="pedido" type="text" id="pedido" class="form-control" list="facturas"  placeholder="buscar factura">
                      <input name="pedido1" type="hidden" id="pedido1" class="form-control" >
                      <datalist id="facturas">

                      </datalist>
                  </div>
                  <div class="col-sm-6">
                      <label for="">Total:</label>
                      <input type="text" class="form-control" name="total" id="total" placeholder="Ingrese Nit del cliente" readonly>
                  </div>
              </div>
                <br>
              <div class="row">
                <div class="col-sm-6">
                    <label for="">Nit:</label>
                    <input type="text" class="form-control" name="nit" id="nit" placeholder="Ingrese Nit del cliente">
                </div>
                <div class="col-sm-6">
                    <label for="">Nombre del Cliente:</label>
                    <input type="text" class="form-control" name="cliente" id="cliente" placeholder="Ingrese nombre del cliente">
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                    <label for="">Subtotal:</label>
                    <input type="text" class="form-control" name="subtotal" id="subtotal" placeholder="Ingrese Nit del cliente" readonly>
                </div>
                <div class="col-sm-6">
                    <label for="">Impuesto:</label>
                    <input type="text" class="form-control" name="impuesto" id="impuesto" placeholder="Ingrese nombre del cliente" readonly>
                </div>
              </div>
                <br>
              <div class="row">
                <div class="col-sm-12">
                    <label for="">Dirección:</label>
                    <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Ingrese direccion del cliente" value="Ciudad">
                </div>
              </div>
                <br>
              <div class="row">
                <div class="col-sm-6">
                    <label for="">Serie de Factura</label>
                    <input type="text" class="form-control" name="serie" placeholder="Ingrese nombre del cliente">
                </div>
                <div class="col-sm-6">
                    <label for="">Número de Factura:</label>
                    <input type="text" class="form-control" name="factura" id="factura" placeholder="Ingrese el numero de factura">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-4">
                    <label for="fecha_ingreso">Fecha</label>

                    <div class="input-group date" id='fecha_fac'>
                        <input type="text" class='form-control clsDatePicker' name='fecha_fac'>
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                    </div>
                </div>
              </div>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnInsertar">Registrar</button>
            </div>
        </div>
        </div>
    </form>
</div>
@push('styles')
<style>
    .clsDatePicker {
    z-index: 100000;
}
</style>
@endpush

@push('scripts')
    <script>

    $('#fecha_fac').datepicker({
        "language": "es",
        "todayHighlight": true,
        "clearBtn": true,
        "autoclose": true,
    });

    $('#modalFactura').on('show.bs.modal', function(e) {
        if (e.namespace === 'bs.modal') {
            $('#fecha_fac').datepicker('setDate', new Date());

        }
    })

    $('#fecha_fac').datepicker().on('hide',function(e){ e.stopPropagation() })

    $("#modalFactura").on('hidden.bs.modal', function () {
              $("#btnInsertar").removeAttr('disabled');
              var btnAceptar=document.getElementById("btnInsertar");
              var disableButton = function() { this.disabled = false; };
              btnAceptar.addEventListener('click', disableButton , true);
     });


    if(window.location.hash === '#insertar')
    {
        $('#modalFactura').modal('show');
        cargarPedidos();
    }

    $('#modalFactura').on('hide.bs.modal', function(){
        $("#InsertarForm").validate().resetForm();
        document.getElementById("InsertarForm").reset();
        window.location.hash = '#';
    });

    $('#modalFactura').one('show.bs.modal', function(){
        window.location.hash = '#insertar';
        cargarPedidos();
    });

    function cargarPedidos(){
      $.ajax({
          url: "{{route('facturas.pedidos')}}"
      }).then(function (data){
          var cuenta = 0;
  //        $("#pedido").empty();
    //      //this block adds a default option for validation
      //    var op = document.createElement("OPTION");
        //  op.append('------------');
          //op.setAttribute("value", 'default');
          //$("#pedido").append(op);
          //this block adds the options from the ajax request
          //while (cuenta < data.length) {
            //  var op = document.createElement("OPTION");
              //op.append('Factura No.' + data[cuenta].no_pedido);
              //op.setAttribute("value", data[cuenta].id);
              //$("#pedido").append(op);
              //cuenta ++;
          //}

          while (cuenta < data.length) {
          var facturas;
          facturas += '<option data-value="'+data[cuenta].id+'" value="Factura No.'+data[cuenta].no_pedido+'">';
          $("#facturas").html(facturas);
          cuenta ++;
      }
      })
    }

    $(document).on('focusout', '#pedido', function(){
      var shownVal= document.getElementById("pedido").value;
      var value2send=document.querySelector("#facturas option[value='"+shownVal+"']").dataset.value;
        console.log(value2send);
  var codigo = value2send;
  $('#pedido1').val(value2send);
  var url = "@php echo url('/') @endphp" + "/facturas/clientes/" + codigo;
  $.ajax({
      url: url,
      success: function(data){
          $('#nit').val(data[0].nit);
          $('#cliente').val(data[0].nombre_cliente);
          $('#direccion').val(data[0].direccion);
          var iva = (12 / 100) * data[0].total;
          var subtotal = data[0]. total - iva;
          $('#subtotal').val(subtotal);
          $('#impuesto').val(iva);
          $('#total').val(data[0].total);
        },
        error: function(){
          alertify.set('notifier', 'position', 'top-center');
          alertify.error  (
              'No se encontró el pedido. Por favor, seleccionar');
              $('#nit').val(null);
              $('#cliente').val(null);
            }
          });
        });

        $("#InsertarForm").submit(function(event){

            event.preventDefault();

            var serializedData = $("#InsertarForm").serialize();
            if ($('#InsertarForm').valid()) {
                $.ajax({
                    type: "POST",
                    headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
                    url: "{{route('facturas.save')}}",
                    data: serializedData,
                    dataType: "json",
                    success: function (data) {
                        $('.loader').fadeOut(225);
                        $('#modalFactura').modal("hide");
                        facturas_table.ajax.reload();
                        //window.location.reload();
                        $('#saldo').text(parseFloat(data.saldo).toFixed(2));
                        alertify.set('notifier', 'position', 'top-center');
                        alertify.success('¡La factura se registró correctamente!');
                            window.location.reload();
                    },
                    error: function (errors) {
                        $('.loader').fadeOut(225);
                        $('#modalAbono').modal("hide");
                        //detalles_table.ajax.reload();
                        alertify.set('notifier', 'position', 'top-center');
                        alertify.error('Hubo un error al registrar la factura');
                    }
                })
        }
        });

        $.validator.addMethod("facturaUnica", function(value, element) {
            var valid = false;
            $.ajax({
                type: "GET",
                async: false,
                url: "{{route('facturas.noFacturaDisponible')}}",
                data: "factura=" + value,
                dataType: "json",
                success: function(msg) {
                    valid = !msg;
                }
            });
            return valid;
        }, "El número de factura ya se encuentra registrado en el sistema");

        $.validator.addMethod("serieUnica", function(value, element) {
            var valid = false;
            var factura = $("input[name='factura']").val();
            var serie = $("input[name='serie']").val();
            $.ajax({
                type: "GET",
                async: false,
                url: "{{route('facturas.noSerieDisponible')}}",
                data: {"serie": serie, 'factura': factura},
                dataType: "json",
                success: function(msg) {
                    valid = !msg;
                }
            });
            return valid;
        }, "El número de serie ya se encuentra registrado en el sistema");


    </script>
    <script src="{{asset('js/facturas/create.js')}}"></script>
@endpush
