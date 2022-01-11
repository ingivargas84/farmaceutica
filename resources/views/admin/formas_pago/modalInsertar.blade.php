<div class="modal fade" id="modalInsertar" tabindex="-1" role="dialog">
    <form method="POST" id="InsertarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Insertar nueva forma de pago</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Nombre de la forma de pago">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnInsertar">Insertar</button>
            </div>
        </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
    $("#modalInsertar").on('hidden.bs.modal', function () {
              $("#btnInsertar").removeAttr('disabled');
              var btnAceptar=document.getElementById("btnInsertar");
              var disableButton = function() { this.disabled = false; };
              btnAceptar.addEventListener('click', disableButton , true);
     });
    if(window.location.hash === '#insertar')
    {
        $('#modalInsertar').modal('show');
    }

    $('#modalInsertar').on('hide.bs.modal', function(){
        $("#InsertarForm").validate().resetForm();
        window.location.hash = '#';
        document.getElementById("InsertarForm").reset();
    });

    $('#modalInsertar').on('shown.bs.modal', function(){
        window.location.hash = '#insertar';
    });

    $('#btn-insertar').on('click', function(){
        $("#InsertarForm").validate().resetForm();
    });


    $("#InsertarForm").submit(function(event){

        event.preventDefault();

        var serializedData = $("#InsertarForm").serialize();
        if ($('#InsertarForm').valid()) {
            $.ajax({
                type: "POST",
                headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
                url: "{{route('formas_pago.save')}}",
                data: serializedData,
                dataType: "json",
                success: function (data) {
                    $('.loader').fadeOut(225);
                    $('#modalInsertar').modal("hide");
                    formas_pago_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.success('La Forma de Pago se Insertó Correctamente!!');
                },
                error: function (errors) {
                    $('.loader').fadeOut(225);
                    $('#modalInsertar').modal("hide");
                    formas_pago_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.error('Ocurrió un error al insertar.');
                }
            })
    }
    });

    </script>
    <script>
    $.validator.addMethod("nombreUnico", function(value, element) {
        var valid = false;
        $.ajax({
            type: "GET",
            async: false,
            url: "{{route('formas_pago.nombreDisponible')}}",
            data: "nombre=" + value,
            dataType: "json",
            success: function(msg) {
                valid = !msg;
            }
        });
        return valid;
    }, "El nombre ya está registrado en el sistema");
    </script>

    <script src="{{asset('js/formas_pago/create.js')}}"></script>
@endpush
