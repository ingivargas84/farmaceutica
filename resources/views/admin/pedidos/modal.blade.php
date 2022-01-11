<!-- Modal -->
<div id="new_order_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Seleccione la bodega para el pedido</h4>
        </div>
        <div class="modal-body">
          <div>
            <form id="bodega_form">
              <div class="form-group">
                <label for="bodega_id">Bodega</label>
                <select class="form-control" name="bodega_id" id="bodegas_select">
                  <option value="0">---------</option>
                </select>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="crear_pedido_button">Crear pedido</button>
        </div>
      </div>
  
    </div>
  </div>

  @push('scripts')
<script>
  $(document).on('click', '#crear_pedido_button', function(e){
    if($('#bodega_form').valid()){
      window.location.assign('/pedidos/new/' + $('#bodegas_select').val())
    }
  });

  $.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
  }, "Debe seleccionar una bodega.");


  var validator = $('#bodega_form').validate({
    ignore: [],
    onkeyup: false,
    rules: {
        bodega_id: {
            required: true,
            select: 'default'
        }
    },
    messages: {
    }
  });

</script>
      
  @endpush