<!-- Modal -->
<div id="modal_visitas" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Registro de Visitas</h4>
        </div>
        <div class="modal-body">
          @role('Super-Administrador|Administrador')
          <div>
              <div class="form-group">
                  <label for="report_date">Fecha</label>
                  <input type="text" class="form-control" name="fecha" id="fecha">
              </div>
              <div class="form-group">
                <label for="vendedor">Seleccione Vendedor</label>
                <select name="vendedor" class="form-control">
                  @foreach ($usuario as $u)
                    <option value="{{$u->id}}">{{$u->username}}</option>
                  @endforeach
                </select>
             </div>
          </div>
          @endrole
          @role('Vendedor')
          <div>
              <div class="form-group">
                  <label for="report_date">Fecha</label>
                  <input type="text" class="form-control" name="fecha" id="fecha">
                  <input type="hidden" class="form-control" name="vendedor" value="{{auth()->user()->id}}">
              </div>
          </div>
        @endrole
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="visitas">Ver Reporte</button>
        </div>
      </div>

    </div>
  </div>
  @push('scripts')

  <script>
          //setting the datepicker
          $('#fecha').datepicker({
              "language": "es",
              "todayHighlight": true,
              "clearBtn": true,
              "autoclose": true,
              "format" : "yyyy-mm-dd"
          });
          //default to current date when opening the client balance report modal
          $('#modal_visitas').on('show.bs.modal', function(e) {
              if (e.namespace === 'bs.modal') {
                  $('#fecha').datepicker('setDate', new Date());
              }
          })
  </script>
@endpush
