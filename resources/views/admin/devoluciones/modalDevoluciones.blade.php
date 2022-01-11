<!-- Modal -->
<div id="modal_devoluciones" class="modal fade" role="dialog">
  <form method="POST" id="devoluciones"  action="{{route('devoluciones.index')}}">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Devoluciones</h4>
        </div>
        <div class="modal-body">
          <div>
              <div class="form-group">
                  <label for="report_date">Selecciones Pedido</label>
                  <input list="browsers1" id="pedidoDevolucion" class="form-control" name="pedidoDevolucion"  placeholder="buscar pedido" required title="Debe seleccionar un pedido.">
                  <datalist id="browsers1">

                  </datalist>
                  <input type="hidden" name="pedido1" id="pedido1" value="">
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btnInsertar">Seleccionar Pedido</button>
        </div>
      </div>

    </div>
    </form>
  </div>
  @push('scripts')


  <script type="text/javascript">

  $('#modal_devoluciones').on('hide.bs.modal', function(){
      $("#devoluciones").validate().resetForm();
      document.getElementById("devoluciones").reset();
      window.location.hash = '#';
  });

  $(document).ready(function(){
          var url = "@php echo url('/') @endphp" + "/devoluciones/pedidos" ;
      $.ajax({
          url: url,
            success: function(data){
                  var pedido_select;
                  for (var i=0; i<data.length;i++)
                  pedido_select += '<option data-value="'+data[i].id+'" value="'+data[i].numero+'-'+data[i].cliente+'-Q.'+data[i].monto+'">';
                  $("#browsers1").html(pedido_select);

      },
      error: function(){
        alertify.set('notifier', 'position', 'top-center');
        alertify.error  ('Error al cargar pedidos');
      }
    });
  });

  $(document).on('focusout', '#pedidoDevolucion', function(){
    var shownVal= document.getElementById("pedidoDevolucion").value;
    var value2send=document.querySelector("#browsers1 option[value='"+shownVal+"']").dataset.value;
      console.log(value2send);
          $('#pedido1').val(value2send);
      });
</script>
<script src="{{asset('js/devoluciones/modalpedido.js')}}"></script>{{-- validator --}}
@endpush
