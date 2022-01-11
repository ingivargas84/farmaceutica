<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
    <form method="POST" id="EditarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Editar Presentación de Producto</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="">Presentación</label>
                        <input type="text" class="form-control" name="presentacion" placeholder="Nombre de la presentación de producto" id="presentacion_ed">
                        <input type="hidden" id='id_edit' name="id">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnActualizar">Actualizar</button>
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
    $(document).on('click', ".edit-presentacion-producto", function(){
        $('#modalEditar').modal('show');
        var id = $(this).parent().parent().attr('id');
        $('#id_edit').val(id);
        cargarPresentacionProducto(id);
    })

/*
carga la presentación de producto a editar y asigna los atributos a los
campos del formulario
*/
    function cargarPresentacionProducto(id){
        $.ajax({
            url: "{{url()->current()}}" + "/edit/" + id,
            headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        }).then(function(data){
            $("#presentacion_ed").val(data.presentacion);
        });
    }

    $('#modalEditar').on('hide.bs.modal', function(){
        $("#EditarForm").validate().resetForm();
        document.getElementById("EditarForm").reset();
        window.location.hash = '#';
    });

    $('#modalEditar').on('shown.bs.modal', function(){
        $("#EditarForm").validate().resetForm();
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
                    presentaciones_producto_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.success('La Presentación de Producto se Editó Correctamente!!');
                },
                error: function (errors) {
                    $('.loader').fadeOut(225);
                    $('#modalEditar').modal("hide");
                    presentaciones_producto_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.error('Ocurrió un error al editar.');
                }
            })
        }
    });

    </script>
    <script src="{{asset('js/presentaciones_producto/edit.js')}}"></script>
@endpush
