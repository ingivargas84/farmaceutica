<!--Modal Ventas por vendedor-->
<div id="modal_saldos_territorios" class="modal fade" role="dialog">
  <form method="POST" id="saldosTerritorios"  action="{{route('reportes.saldosT')}}">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reporte de Saldos de Clientes por Territorio</h4>
        </div>
        <div class="modal-body">
          <div>
              <div class="form-group">
                  <label for="report_date">Fecha</label>
                  <input type="text" class="form-control" name="fecha" id="fecha">
              </div>
              <div class="form-group">
                <label for="territorios">Seleccione Territorio</label>
                <select name="territorios" id="territorios" class="form-control">
                  <option value="todos">Todos</option>
                </select>
                </select>
             </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="saldosterritorios">Ver Reporte</button>
        </div>
       </div>
      </div>
    </form>
  </div>
  @push('scripts')

  <script>
          //setting the datepicker
          $('#fecha').datepicker({
              "language": "es",
              "todayHighlight": true,
              "clearBtn": true,
              "autoclose": true,
          });
          //default to current date when opening the client balance report modal
          $('#modal_saldos_territorios').on('show.bs.modal', function(e) {
              if (e.namespace === 'bs.modal') {
                  $('#fecha').datepicker('setDate', new Date());
              }

          })



        $(document).ready(function(){
                var url = "@php echo url('/') @endphp" + "/reportes/territorios" ;
            $.ajax({
                url: url,
                  success: function(data){
                        var usuario_select= '<option value="todos">Todos</option>';
                        for (var i=0; i<data.length;i++)
                        usuario_select += '<option value="'+data[i].id+'">'+data[i].territorio+'</option>';
                        $("#territorios").html(usuario_select);
            },
            error: function(){
              alertify.set('notifier', 'position', 'top-center');
              alertify.error  ('Error al cargar territorios');
            }
          });
        });



  </script>
@endpush
