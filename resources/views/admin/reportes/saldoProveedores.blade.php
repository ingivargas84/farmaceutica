<!--Modal Ventas por vendedor-->
<div id="modal_proveedores" class="modal fade" role="dialog">
  <form method="POST" id="saldoProveedores"  action="{{route('reportes.proveedores')}}">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Saldo de Proveedores</h4>
        </div>
        <div class="modal-body">
          <div>
              <div class="form-group">
                  <label for="report_date">Fecha</label>
                  <input type="text" class="form-control" name="fechap" id="fechap">
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="saldoProveedores">Ver Reporte</button>
        </div>
       </div>
      </div>
    </form>
  </div>
  @push('scripts')

  <script>
          //setting the datepicker
          $('#fechap').datepicker({
              "language": "es",
              "todayHighlight": true,
              "clearBtn": true,
              "autoclose": true,
              "format" : "yyyy-mm-dd"
          });

          //default to current date when opening the client balance report modal
          $('#modal_proveedores').on('show.bs.modal', function(e) {
              if (e.namespace === 'bs.modal') {
                  $('#fechap').datepicker('setDate', new Date());
              }

          })
  </script>
@endpush
