<!-- Modal -->
<div id="modal_reporteMensual" class="modal fade" role="dialog">
  <form method="POST" id="reporteMensual"  action="{{route('reportes.reporteMes')}}">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reporte Mes</h4>
        </div>
        <div class="modal-body">
          <div>
              <div class="form-group">
                  <label for="report_date">Selecciones Mes</label>
                  <select name="mes" id="mes" class="form-control">
                      <option value="default">--------------</option>
                      <option value="1">Enero</option>
                      <option value="2">Febrero</option>
                      <option value="3">Marzo</option>
                      <option value="4">Abril</option>
                      <option value="5">Mayo</option>
                      <option value="6">Junio</option>
                      <option value="7">Julio</option>
                      <option value="8">Agosto</option>
                      <option value="9">Septiempre</option>
                      <option value="10">Ocutbre</option>
                      <option value="11">Noviembre</option>
                      <option value="12">Diciembre</option>
                  </select>
              </div>
              <div class="form-group">
                <label for="vendedor">Seleccione Año</label>
                <select name="anio" id="anio" class="form-control">
                    <option value="default">--------------</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
             </div>
             <input type="hidden" name="dias" id="dias" value="">
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

  $("#modal_reporteMensual").on('hidden.bs.modal', function () {
            $("#btnInsertar").removeAttr('disabled');
            var btnAceptar=document.getElementById("btnInsertar");
            var disableButton = function() { this.disabled = false; };
            btnAceptar.addEventListener('click', disableButton , true);
   });


  if(window.location.hash === '#insertar')
  {
      $('#modal_reporteMensual').modal('show');
  }

  $('#modal_reporteMensual').on('hide.bs.modal', function(){
      $("#reporteMensual").validate().resetForm();
      document.getElementById("reporteMensual").reset();
      window.location.hash = '#';
  });

  $('#modalFactura').on('show.bs.modal', function(){
      window.location.hash = '#insertar';
  });



  $(document).on('focusout', '#mes, #anio', function(){
        var año = $('#anio').val();
        var mes = $('#mes').val();
        var dias = new Date(año, mes, 0).getDate();
        $('#dias').val(dias);
      });
  </script>
    <script src="{{asset('js/reportes/reportemensual.js')}}"></script>
@endpush
