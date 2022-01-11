<div class="modal fade" id="modalInsertar" tabindex="-1" role="dialog">
    <form method="POST" id="InsertarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Insertar nueva bodega</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Nombre de la bodega">
                    </div>
                    <div class="col-sm-6">
                        <label for="">Descripción </label>
                        <input type="text" class="form-control" name="descripcion" placeholder="Descripción de la bodega">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="tipo">Tipo de Bodega</label>
                        <select name="tipo" id="select_tipos" class="form-control">
                            <option value="">-------------</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="encargado">Encargado de Bodega</label>
                        <select name="user_id" id="select_encargado" class="form-control">
                            <option value="">-------------</option>
                        </select>
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
        cargarTipos();
        cargarTipos1();
    }

    $('#modalInsertar').on('hide.bs.modal', function(){
        $("#InsertarForm").validate().resetForm();
        document.getElementById("InsertarForm").reset();
        window.location.hash = '#';
    });

    $('#modalInsertar').on('shown.bs.modal', function(){
        window.location.hash = '#insertar';
        cargarTipos();
        cargarTipos1();
    });

/*
Obtiene los tipos de bodega, genera elementos 'option' y los
agrega al select de tipos de bodega.
*/
    function cargarTipos(){
        $.ajax({
            url:"{{route('bodegas.new')}}"
        }).then(function (data){
            var cuenta = 0;
            $("#select_tipos").empty();
            while (cuenta < data.length) {
                var op = document.createElement("OPTION");
                op.append(data[cuenta].tipo);
                op.setAttribute("value", data[cuenta].id);
                $("#select_tipos").append(op);
                cuenta ++;
            }
        })
    }


    function cargarTipos1(){
        $.ajax({
            url:"{{route('bodegas.new1')}}"
        }).then(function (data){
            var cuenta = 0;
            $("#select_encargado").empty();
            if (data == "") {
                var op = document.createElement("OPTION");
              op.append("Sin usuarios para asignar");
              op.setAttribute("value", '0');
              $("#select_encargado").append(op);
            }else {
              while (cuenta < data.length) {
                  var op = document.createElement("OPTION");
                  op.append(data[cuenta].name);
                  op.setAttribute("value", data[cuenta].id);
                  $("#select_encargado").append(op);
                  cuenta ++;
              }
            }

        })
    }

    $("#InsertarForm").submit(function(event){

        event.preventDefault();

        var serializedData = $("#InsertarForm").serialize();
        if ($('#InsertarForm').valid()) {
            $.ajax({
                type: "POST",
                headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
                url: "{{route('bodegas.save')}}",
                data: serializedData,
                dataType: "json",
                success: function (data) {
                    $('.loader').fadeOut(225);
                    $('#modalInsertar').modal("hide");
                    bodegas_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.success('La Bodega se Insertó Correctamente!!');
                },
                error: function (errors) {
                    $('.loader').fadeOut(225);
                    $('#modalInsertar').modal("hide");
                    bodegas_table.ajax.reload();
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.error('Ocurrió un error al insertar.');
                }
            })
    }
    });

    </script>
    <script src="{{asset('js/bodegas/create.js')}}"></script>
@endpush
