<div class="modal fade" id="modalAbono" tabindex="-1" role="dialog">
    <form method="POST" id="InsertarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Registrar un abono a la cuenta.</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                  <div class="col-sm-6">
                      <label for="">Seleccione Factura A Pagar</label>
                      <select name="factura"  id="factura" class="form-control">
                          <option value="">-------------</option>

                      </select>
                  </div>
              </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="monto">Monto</label>
                        <input type="number" class="form-control" name="monto" id="monto" placeholder="Cantidad de dinero a registrar" readonly>
                    </div>

                    <div class="col-sm-6">
                        <label for="fecha_ingreso">Fecha</label>

                        <div class="input-group date" id='fecha_ingreso'>
                            <input type="text" class='form-control clsDatePicker' name='fecha_ingreso'>
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                    </div>

                </div>
                <br>
                <input type="hidden" name="user" value="{{auth()->user()->id}}">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="forma_pago">Forma de Pago</label>
                        <select name="forma_pago" id="select_formas" class="form-control">
                            <option value="">-------------</option>
                        </select>
                        <input type="hidden" name='cuentas_pagar_maestro_id' id="cuentas_pagar_maestro_id" value="{{$cuenta[0]->id}}">
                    </div>
                    <div class="col-sm-6">
                        <label for="no_documento">No. Documento </label>
                        <input type="text" class="form-control" name="no_documento" placeholder="No. del documento (opcional)">
                    </div>
                </div>
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
    $("#modalAbono").on('hidden.bs.modal', function () {
              $("#btnInsertar").removeAttr('disabled');
              var btnAceptar=document.getElementById("btnInsertar");
              var disableButton = function() { this.disabled = false; };
              btnAceptar.addEventListener('click', disableButton , true);
     });
        $('#fecha_ingreso').datepicker({
            "language": "es",
            "todayHighlight": true,
            "clearBtn": true,
            "autoclose": true,
        });

        $('#modalAbono').on('show.bs.modal', function(e) {
            if (e.namespace === 'bs.modal') {
                $('#fecha_ingreso').datepicker('setDate', new Date());

            }
        })

        $('#fecha_ingreso').datepicker().on('hide',function(e){ e.stopPropagation() })

    if(window.location.hash === '#insertar')
    {
        $('#modalAbono').modal('show');
        var id = document.getElementById("cuentas_pagar_maestro_id").value
        cargarTipos();
        cargarFacturas();
    }

    $('#modalAbono').on('hide.bs.modal', function(){
        $("#InsertarForm").validate().resetForm();
        document.getElementById("InsertarForm").reset();
        window.location.hash = '';
    });

    $('#modalAbono').one('show.bs.modal', function(){
        window.location.hash = '#insertar';
        var id = document.getElementById("cuentas_pagar_maestro_id").value;
        cargarTipos();
        cargarFacturas();
    });

/*
Obtiene las formas de pago, genera elementos 'option' y los
agrega al select de tipos de bodega.
*/
    function cargarTipos(){
        $.ajax({
            url:"{{route('cuentas_pagar.new')}}"
        }).then(function (data){
            var cuenta = 0;
            $("#select_formas").empty();
            //this block adds a default option for validation
            var op = document.createElement("OPTION");
            op.append('------------');
            op.setAttribute("value", 'default');
            $("#select_formas").append(op);
            //this block adds the options from the ajax request
            while (cuenta < data.length) {
                var op = document.createElement("OPTION");
                op.append(data[cuenta].nombre);
                op.setAttribute("value", data[cuenta].id);
                $("#select_formas").append(op);
                cuenta ++;
            }
        })
    }

    function cargarFacturas(id){
      var id = document.getElementById("cuentas_pagar_maestro_id").value

        $.ajax({
            url: "/cuentas_pagar/facturas/" + id
        }).then(function (data){
            var cuenta = 0;
            $("#factura").empty();
            //this block adds a default option for validation
            var op = document.createElement("OPTION");
            op.append('------------');
            op.setAttribute("value", 'default');
            $("#factura").append(op);
            //this block adds the options from the ajax request
            while (cuenta < data.length) {
                var op = document.createElement("OPTION");
                op.append('Factura No.' + data[cuenta].factura);
                op.setAttribute("value", data[cuenta].id_c);
                $("#factura").append(op);
                cuenta ++;
            }
        })
    }



    $("#InsertarForm").submit(function(event){

        event.preventDefault();

        var serializedData = $("#InsertarForm").serialize();
        if ($('#InsertarForm').valid()) {
            $.ajax({
                type: "POST",
                headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
                url: "{{route('cuentas_pagar.save')}}",
                data: serializedData,
                dataType: "json",
                success: function (data) {
                    $('.loader').fadeOut(225);
                    $('#modalAbono').modal("hide");
                    detalles_table.ajax.reload();
                    //window.location.reload();
                    cargarTipos();
                    cargarFacturas();
                    $('#saldo').text(parseFloat(data.saldo).toFixed(2));
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.success('¡El abono se registró correctamente!');
                },
                error: function (errors) {
                    $('.loader').fadeOut(225);
                    $('#modalAbono').modal("hide");
                    //detalles_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.error('No puede abonar una cantidad mayor al saldo ni abonar cuando el saldo es 0');
                }
            })
    }
    });


    $(document).on('focusout', '#factura', function(){
  var codigo = $('#factura').val();
  var url = "@php echo url('/') @endphp" + "/cuentas_pagar/monto/" + codigo;
  $.ajax({
      url: url,
      success: function(data){
          $('#monto').val(data[0].total_ingreso);
        },
        error: function(){
          alertify.set('notifier', 'position', 'top-center');
          alertify.error  (
              'No se encontró la factura. Por favor, seleccionar');
              $('#monto').val(null);
            }
          });
        });

    </script>
    <script src="{{asset('js/cuentas_pagar/create.js')}}"></script>
@endpush
