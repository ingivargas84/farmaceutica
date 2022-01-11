<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
    <form method="POST" id="EditarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Editar Territorio</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="">Territorio</label>
                        <input type="text" class="form-control" name="territorio" placeholder="Nombre de la bodega" id="name_ed">
                    </div>
                    <div class="col-sm-6">
                        <label for="">Vendedor</label>
                        <select class="form-control" name="user" id="user">

                        </select>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="">Descripción</label>
                        <input type="text" class="form-control" name="descripcion" placeholder="Descripción de la bodega" id="desc_ed">
                        <input type="hidden" id='id_edit' name="id">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnActualizar">Guardar</button>
            </div>
        </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
      var p;
    $("#modalEditar").on('hidden.bs.modal', function () {
              $("#btnActualizar").removeAttr('disabled');
              var btnAceptar=document.getElementById("btnActualizar");
              var disableButton = function() { this.disabled = false; };
              btnAceptar.addEventListener('click', disableButton , true);
     });
    $(document).on('click', ".edit-territorio", function(e){
        e.preventDefault();
        $('#modalEditar').modal('show');
        var id = $(this).parent().parent().attr('id');
        $('#id_edit').val(id);
        cargarTerritorio(id);
        cargarUsuarioterri();
    })

/*
carga la bodega a editar y asigna los atributos a los
campos del formulario
*/
    function cargarTerritorio(id){

        $.ajax({
            url: "{{url()->current()}}" + "/edit/" + id,
            headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        }).then(function(data){
            $("#name_ed").val(data[0].territorio);
            $("#desc_ed").val(data[0].descripcion);
            p = data[0].usuario_id;
            if (data[0].usuario == null) {
              $("#user").empty();
              var op = document.createElement("OPTION");
              op.append("Sin vendedor asignado");
              op.setAttribute("value", 0);
              $("#user").append(op);
            }else {
              $("#user").empty();
              var op = document.createElement("OPTION");
              op.append(data[0].usuario);
              op.setAttribute("value", data[0].usuario_id);
              $("#user").append(op);
            }

        });
    }

    function cargarUsuarioterri(){

              $.ajax({
                  url:"{{route('territorios.new1')}}"
              }).then(function (data){
                  var cuenta = 0;
                  console.log(p);
              //    $("#select_encargado_edit").empty();
                  while (cuenta < data.length) {
                    if (p !== data[cuenta].id) {
                      var op = document.createElement("OPTION");
                      op.append(data[cuenta].name);
                      op.setAttribute("value", data[cuenta].id);
                      $("#user").append(op);
                      cuenta ++;
                    }else {
                      cuenta++;
                    }
                  }
                  if (p !== null){
                    var op = document.createElement("OPTION");
                    op.append("Dejar sin Vendedor asignado");
                    op.setAttribute("value", 0);
                    $("#user").append(op);
                  }
              })
    }

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
        var serializedData = $("#EditarForm").serialize();

        if ($('#EditarForm').valid()) {
            $.ajax({
                type: "PUT",
                headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
                url: "{{url()->current()}}" + "/" + id + "/update",
                data: serializedData,
                dataType: "json",
                success: function (data) {
                    $('.loader').fadeOut(225);
                    $('#modalEditar').modal("hide");
                    territorios_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.success('La Bodega se Editó Correctamente!!');
                },
                error: function (errors) {
                    $('.loader').fadeOut(225);
                    $('#modalEditar').modal("hide");
                    bodegas_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.error('Ocurrió un error al editar.');
                }
            })
        }
    });

    </script>
    <script src="{{asset('js/territorios/edit.js')}}"></script>
@endpush
