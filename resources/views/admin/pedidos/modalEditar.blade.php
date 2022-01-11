<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
    <form method="POST" id="EditarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Editar Detalle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-sm-4">
                      <label for="">Producto</label>
                      <input type="text" class="form-control" name="productodetalle" placeholder="nombre del producto" id="productodetalle" readonly>
                  </div>
                    <div class="col-sm-4">
                        <label for="">Cantidad</label>
                        <input type="text" class="form-control" name="cantidaddetalle" placeholder="cantidad del producto" id="cantidaddetalle">
                        <input type="hidden" name="preciodetalle" value="" id="preciodetalle">
                        <input type="hidden" class="form-control" name="cantidaddetalletotal" placeholder="cantidad del producto" id="cantidaddetalletotal">

                    </div>
                </div>
                <div class="row">
                  <div class="col-sm-4">
                      <label for="">Subtotal</label>
                      <input type="text" class="form-control" name="subtotaldetalle" placeholder="Subtotal" id="subtotaldetalle" readonly>
                      <input type="hidden" class="form-control" name="subtotaldetalleA" placeholder="Subtotal" id="subtotaldetalleA" readonly>
                      <input type="hidden" id='id_edit' name="idDetalle">
                      <input type="hidden" id='usuario' name="id_usuario">
                      <input type="hidden" id="bodegadetalle" name="bodegadetalle" value="">
                      <input type="hidden" id="productodetalleid" name="productodetalleid" value="">
                      <input type="hidden" name="idproductodetalle" value="" id="idproductodetalle">
                  </div>
                  <div class="col-sm-4">
                      <label for="">Existencias</label>
                      <input type="text" class="form-control" name="existencias" placeholder="existencias del producto" id="existencias" readonly>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnActualizar1">Editar</button>
            </div>
        </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
    $(document).on('click', ".edit-bodega", function(){
        $('#modalEditar').modal('show');
        var id = $(this).parent().parent().attr('id');
        $('#id_edit').val(id);
        cargarDetalle(id);
    })

/*
carga la bodega a editar y asigna los atributos a los
campos del formulario
*/
    function cargarDetalle(id){
        $.ajax({
            url: "/pedidos/editarDetalle/" + id,
            headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        }).then(function(data){
            $("#productodetalle").val(data[0].producto);
            $("#cantidaddetalle").val(data[0].cantidad);
            $("#cantidaddetalletotal").val(data[0].cantidad);
            $("#preciodetalle").val(data[0].precio);
            $("#subtotaldetalle").val(data[0].subtotal);
            $("#subtotaldetalleA").val(data[0].subtotal);
            $("#bodegadetalle").val(data[0].bodega);
            $("#productodetalleid").val(data[0].productoid);
            $("#idproductodetalle").val(data[0].id);
            var codigo = $('#productodetalleid').val();
            var bodega = $('#bodegadetalle').val();
            var url =  "@php echo url('/') @endphp" + "/pedidos/getProductoData/" + codigo + "/"+ bodega;
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
                        $('#existencias').val(data[0].existencias);
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

    }

    $(document).on('focusout', '#cantidaddetalle', function(){
      var cantidad = $('#cantidaddetalle').val();
      var precio  = $('#preciodetalle').val();
      var subtotaldetalle = cantidad * precio;
      $("#subtotaldetalle").val(subtotaldetalle);

        });

    $('#modalEditar').on('hide.bs.modal', function(){
        $("#EditarForm").validate().resetForm();
        document.getElementById("EditarForm").reset();
        window.location.hash = '#';
    });

    $('#modalEditar').on('shown.bs.modal', function(){
        window.location.hash = '#editar';
    });


    $("#EditarForm").submit(function(event){
        event.preventDefault();
        var id = $('#id_edit').val();
        if (parseFloat($('#subtotaldetalle').val()) > parseFloat($('#subtotaldetalleA').val())) {
          var sub =   parseFloat($('#subtotaldetalle').val()) - parseFloat($('#subtotaldetalleA').val());
          var subt = parseFloat($('#subtotaldetalleA').val()) + sub;
        }else {
          var sub =   parseFloat($('#subtotaldetalleA').val()) - parseFloat($('#subtotaldetalle').val());
          var subt = parseFloat($('#subtotaldetalleA').val()) - sub;
        }

        var serializedData = $("#EditarForm").serialize();
          if(chkflds1()){
            if ($('#EditarForm').valid()) {
                $.ajax({
                    type: "PUT",
                    headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
                    url: "/pedidos/actulizarDetalle/" + id ,
                    data: serializedData,
                    dataType: "json",
                    success: function (data) {
                        $('.loader').fadeOut(225);
                        $('#modalEditar').modal("hide");
                        var total = 0;
                        detalles_table.column(3).data().each(function(value, index){
                        total = parseFloat(subt) + parseFloat(value);
                        parseFloat(total);
                        $('#total').val(total);
                        $('#total-error').remove();
                    });
                        detalles_table.ajax.reload();
                        alertify.set('notifier', 'position', 'top-center');
                        alertify.success('El detalle se Editó Correctamente!!');
                        window.location.assign('{{url()->current()}}?ajaxSuccess')
                    },
                    error: function (errors) {
                        $('.loader').fadeOut(225);
                        $('#modalEditar').modal("hide");
                        detalles_table.ajax.reload();
                        alertify.set('notifier', 'position', 'top-center');
                        alertify.error('Ocurrió un error al editar.');
                    }
                })
            }
          }else {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error  ('Debe seleccionar  una cantidad menor o igual a las existencias en bodega')
          }

    });

    </script>
    <script type="text/javascript">

    function chkflds() {
        if ( $('#cantidaddetalle').valid()) {
            return true
        }else{
            return false
        }
    }
    //gets the selected prduct data and sets the readonly inputs
    $(document).on('focusout', '#cantidaddetalle', function(){
        var codigo = $('#productodetalleid').val();
        var bodega = $('#bodegadetalle').val();
        var url =  "@php echo url('/') @endphp" + "/pedidos/getProductoData/" + codigo + "/"+ bodega;
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
                    $('#existencias').val(data[0].existencias);
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
        function chkflds1() {
        var t = 0;
        t = parseFloat($('#cantidaddetalletotal').val()) + parseFloat($('#existencias').val());
            if ($('#cantidaddetalle').val() <= t) {
                return true
            }else{
                return false
            }
        }
    </script>
    <script src="{{asset('js/bodegas/edit.js')}}"></script>
    <script src="{{asset('js/pedidos/showEdit.js')}}"></script>{{-- validator --}}
@endpush
