<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
    <form method="POST" id="EditarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Editar bodega</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Nombre de la bodega" id="name_ed">
                    </div>
                    <div class="col-sm-6">
                        <label for="">Descripción</label>
                        <input type="text" class="form-control" name="descripcion" placeholder="Descripción de la bodega" id="desc_ed">
                        <input type="hidden" id='id_edit' name="id">
                        <input type="hidden" id='usuario' name="id_usuario">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="tipo">Tipo de Bodega</label>
                        <select name="tipo" id="select_tipos_ed" class="form-control">
                            <option value="">-------------</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="encargado">Encargado de Bodega</label>
                        <select name="user_id_edit" id="select_encargado_edit" class="form-control">
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnActualizar">Editar</button>
            </div>
        </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
    $("#modalEditar").on('hidden.bs.modal', function () {
              $("#btnActualizar").removeAttr('disabled');
              var btnAceptar=document.getElementById("btnActualizar");
              var disableButton = function() { this.disabled = false; };
              btnAceptar.addEventListener('click', disableButton , true);
     });
    $(document).on('click', ".edit-bodega", function(){
        $('#modalEditar').modal('show');
        var id = $(this).parent().parent().attr('id');
        $('#id_edit').val(id);
        cargarTipos_ed();
        cargarBodega(id);
        cargarUsuario(id);
        cargarTipos2();
    })

/*
carga la bodega a editar y asigna los atributos a los
campos del formulario
*/
    function cargarBodega(id){
        $.ajax({
            url: "{{url()->current()}}" + "/edit/" + id,
            headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        }).then(function(data){
            $("#desc_ed").val(data.descripcion);
            $("#name_ed").val(data.nombre);
            $("#usuario").val(data.user_id)
            $('#select_tipos_ed > option').each(function(){
                if ($(this).val() == data.tipo) {
                    $(this).attr('selected', 'selected');
                }
            });
        });
    }

    function cargarUsuario(id){
        $.ajax({
            url: "{{url()->current()}}" + "/edit1/" + id,
            headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        }).then(function(data){
              $("#select_encargado_edit").empty();
              var op = document.createElement("OPTION");
              op.append(data.name);
              op.setAttribute("value", data.id);
              $("#select_encargado_edit").append(op);
        });
    }

    function cargarTipos2(){
        $.ajax({
            url:"{{route('bodegas.new1')}}"
        }).then(function (data){
            var cuenta = 0;
        //    $("#select_encargado_edit").empty();
            while (cuenta < data.length) {
                var op = document.createElement("OPTION");
                op.append(data[cuenta].name);
                op.setAttribute("value", data[cuenta].id);
                $("#select_encargado_edit").append(op);
                cuenta ++;
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

/*
Obtiene los tipos de bodega, genera elementos 'option' y los
agrega al select de tipos de bodega.
*/
    function cargarTipos_ed(){
        $.ajax({
            url:"{{route('bodegas.new')}}"
        }).then(function (data){
            var cuenta = 0;
            $("#select_tipos_ed").empty();
            while (cuenta < data.length) {
                var op = document.createElement("OPTION");
                op.append(data[cuenta].tipo);
                op.setAttribute("value", data[cuenta].id);
                $("#select_tipos_ed").append(op);
                cuenta ++;
            }
        });
    }

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
                    bodegas_table.ajax.reload();
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
    <script src="{{asset('js/bodegas/edit.js')}}"></script>
@endpush
