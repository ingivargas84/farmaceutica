<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
    <form method="POST" id="EditarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Editar Nota de Envío</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="">Cliente:</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Nombre del cliente" id="nombre">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="">Dirección:</label>
                        <input type="text" class="form-control" name="direccion" placeholder="Dirección del cliente" id="direccion">
                        <input type="hidden" id='id_edit' name="id">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="tipo">Teléfono:</label>
                        <input type="text" class="form-control" placeholder='Teléfono del cliente' name="telefono" id="telefono">
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

    $(document).on('click', ".edit-nota-envio", function(){
        $('#modalEditar').modal('show');
        var id = $(this).parent().parent().attr('id');
        $('#id_edit').val(id);
        cargarNota(id);
    });

/*
carga la bodega a editar y asigna los atributos a los
campos del formulario
*/
    function cargarNota(id){
        url = "{{url()->current()}}" + "/edit/" + id;
        $.ajax({
            url: url,
            headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        }).then(function(data){
            $('#nombre').val(data.cliente);
            $('#direccion').val(data.direccion);
            $('#telefono').val(data.telefono)
        });
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
                    notas_envio_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.success('La nota de envío se editó correctamente!!');
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
    <script src="{{asset('js/notas_envio/edit.js')}}"></script>
@endpush