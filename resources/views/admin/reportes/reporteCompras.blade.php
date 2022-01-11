<!--Modal Ventas por vendedor-->
<div id="modal_compras" class="modal fade" role="dialog">
  <form method="POST" id="ventasVendedor"  action="{{route('reportes.comprasP')}}">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reporte de Compras por Fecha y Proveedor</h4>
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
              <div class="form-group">
                <label for="vendedor">Seleccione Proveedor</label>
                <select name="proveedor" id="proveedor" class="form-control">
                  <option value="todos">Todos</option>
                </select>
                </select>
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
          $('#modal_compras').on('show.bs.modal', function(e) {
              if (e.namespace === 'bs.modal') {
                  $('#fechaInicial, #fechaFinal').datepicker('setDate', new Date());
              }

          })



        $(document).ready(function(){
                var url = "@php echo url('/') @endphp" + "/reportes/compras" ;
            $.ajax({
                url: url,
                  success: function(data){
                        var usuario_select= '<option value="todos">Todos</option>';
                        for (var i=0; i<data.length;i++)
                        usuario_select += '<option value="'+data[i].id+'">'+data[i].nombre_comercial+'</option>';
                        $("#proveedor").html(usuario_select);
            },
            error: function(){
              alertify.set('notifier', 'position', 'top-center');
              alertify.error  ('Error al cargar proveedores');
            }
          });
        });



  </script>
@endpush
