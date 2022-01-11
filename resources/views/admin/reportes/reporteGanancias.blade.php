<!--Modal Ventas por vendedor-->
<div id="modal_ganancias" class="modal fade" role="dialog">
  <form method="POST" id="ganancias"  action="{{route('reportes.reporteGanancias')}}">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reporte de Ganancias</h4>
        </div>
        <div class="modal-body">
          <div>
              <div class="form-group">
                  <label for="report_date">Fecha Inicial</label>
                  <input type="text" class="form-control" name="fechaInicial" id="fechaInicial">
              </div>
              <div class="form-group">
                  <label for="report_date">Fecha Final</label>
                  <input type="text" class="form-control" name="fechaFinal" id="fechaFinal">
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="ventas">Ver Reporte</button>
        </div>
       </div>
      </div>
    </form>
  </div>
  @push('scripts')

  <script>
          //setting the datepicker
          $('#fechaInicial, #fechaFinal').datepicker({
              "language": "es",
              "todayHighlight": true,
              "clearBtn": true,
              "autoclose": true,
          });
          //default to current date when opening the client balance report modal
          $('#modal_ganancias').on('show.bs.modal', function(e) {
              if (e.namespace === 'bs.modal') {
                  $('#fechaInicial, #fechaFinal').datepicker('setDate', new Date());
              }

          })






  </script>
@endpush
