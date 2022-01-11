<!-- Modal -->
<div id="modal_liquidacion" class="modal fade" role="dialog">
  <form method="POST" id="liquidacionMensual"  action="{{route('reportes.liquidacionMensual')}}">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Liquidación Mensual</h4>
        </div>
        <div class="modal-body">
          <div>
              <div class="form-group">
                  <label for="report_date">Selecciones Mes</label>
                  <select name="mes" id="mes" class="form-control">
                      <option value="1">Enero</option>
                      <option value="2">Febrero</option>
                      <option value="3">Marzo</option>
                      <option value="4">Abril</option>
                      <option value="5">Mayo</option>
                      <option value="6">Junio</option>
                      <option value="7">Julio</option>
                      <option value="8">Agosto</option>
                      <option value="9">Septiempre</option>
                      <option value="10">Octubre</option>
                      <option value="11">Noviembre</option>
                      <option value="12">Diciembre</option>
                  </select>
              </div>
              <div class="form-group">
                <label for="vendedor">Seleccione Vendedor</label>
                <select name="usuarioLiquidacion" id="usuarioLiquidacion" class="form-control">
                </select>
             </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btnInsertar">Ver Reporte</button>
        </div>
      </div>

    </div>
    </form>
  </div>
  @push('scripts')


  <script type="text/javascript">



  $(document).ready(function(){
          var url = "@php echo url('/') @endphp" + "/reportes/usuarios" ;
      $.ajax({
          url: url,
            success: function(data){
                  var usuario_select;
                  for (var i=0; i<data.length;i++)
                  usuario_select += '<option value="'+data[i].id+'">'+data[i].username+'</option>';
                  $("#usuarioLiquidacion").html(usuario_select);
      },
      error: function(){
        alertify.set('notifier', 'position', 'top-center');
        alertify.error  ('Error al cargar usuarios');
      }
    });
  });
</script>


@endpush
