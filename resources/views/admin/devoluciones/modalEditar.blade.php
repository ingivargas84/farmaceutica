<div class="modal fade" id="modalEditardevolucion" tabindex="-1" role="dialog">
    <form method="POST" id="EditarForm">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" id="cerrar" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Editar Detalle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="">Cantidad</label>
                        <input type="text" class="form-control" name="cantidad" placeholder="cantidad del producto" id="cantidad" value="1">
                        <input type="hidden" class="form-control" name="cantidad1" placeholder="cantidad del producto" id="cantidad1">
                        <input type="hidden" class="form-control" name="restar" placeholder="cantidad del producto" id="restar">
                        <input type="hidden" id='id_edit' name="idDetalle">
                          <input type="hidden" id='maestro' name="maestro">
                        <input type="hidden" id='producto' name="producto">
                        <input type="hidden" id='precio' name="precio">
                        <input type="hidden" id='idproducto' name="idproducto">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn btn-default" id="cancelar">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnActualizar1">Editar</button>
            </div>
        </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
    var datos  = [];
    var contador = 0;
                    contador = contador + 1;
    $(document).on('click', ".edit-devolucion", function(){
        $('#modalEditardevolucion').modal('show');
        var id = $(this).parent().parent().attr('id');
        var cantidad = $(this).parent().parent().attr('cantidad');
        var restar = $(this).parent().parent().attr('restar');
        var maestro = $(this).parent().parent().attr('maestro');
        $('#id_edit').val(id);
        $('#cantidad').val(cantidad);
        $('#cantidad1').val(cantidad);
        $('#restar').val(restar);
        $('#maestro').val(maestro);
        cargarDetalle(id);
    })


    $(document).on('click', ".delete-devolucion", function(){
        var id = $(this).parent().parent().attr('id');
        var cantidad = $(this).parent().parent().attr('cantidad');
        var restar = $(this).parent().parent().attr('restar');
        var posicion = $(this).parent().parent().attr('posicion');
        cantidad = cantidad * (-1);
        $.ajax({
            url: "/pedidos/editarDetalle/" + id,
            headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        }).then(function(data){
          var subI = data[0].precio * cantidad;

          detalles_table.row.add({
          'id': id,
          'producto': data[0].producto,
          'cantidad': cantidad,
          'precio': data[0].precio,
          'subtotal': subI,
          'idproducto': data[0].id,
          'restar': 0,
            'maestro': $('#maestro').val(),
          }).draw();
        });
        for (var i=0; i<datos.length;i++)
        {
          if (posicion == datos[i].posicion){
            datos.splice(i, 1);
          }
        }
    })
/*
carga la bodega a editar y asigna los atributos a los
campos del formulario
*/
    function cargarDetalle(id){

        $.ajax({
            url: "/pedidos/editarDetalle/" + id,
            headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        }).then(function(data){
          //  $("#cantidad").val(data[0].cantidad);
            $("#producto").val(data[0].producto);
            $("#precio").val(data[0].precio);
            $("#precio1").val(data[0].precio);
            $("#idproducto").val(data[0].id);
        });

    }

    $("#EditarForm").submit(function(event){
        event.preventDefault();
        if ($('#EditarForm').valid()) {
            var sub = 0;
            var can =  parseFloat($('#cantidad1').val()) - parseFloat($('#cantidad').val())
            var can1 = can;

            sub =  parseFloat($('#precio').val()) * parseFloat(can);
            sub = sub * (-1);
            can = can * (-1);
            subtotal =  parseFloat($('#precio').val()) * parseFloat($('#cantidad').val())
            if(can1 > 0){
                detalles_table.row.add({
                'id': $('#id_edit').val(),
                'producto': $('#producto').val(),
                'cantidad': can,
                'precio': $('#precio').val(),
                'subtotal': sub,
                'idproducto': $('#idproducto').val(),
                'restar': can,
                'posicion': contador,
                  'maestro': $('#maestro').val()
                }).draw();

                datos.push({
                'id': $('#id_edit').val(),
                'producto': $('#producto').val(),
                'cantidad': can1,
                'precio': $('#precio').val(),
                'subtotal': sub,
                'idproducto': $('#idproducto').val(),
                'restar': can,
                'posicion': contador,
                'maestro': $('#maestro').val()
                });

                console.log(datos);

           }
        detalles_table.row.add({
            'id': $('#id_edit').val(),
            'producto': $('#producto').val(),
            'cantidad': $('#cantidad').val(),
            'precio': $('#precio').val(),
            'subtotal': subtotal,
            'idproducto': $('#idproducto').val(),
            'restar': 0,
              'posicion': contador,
                'maestro': $('#maestro').val(),
        }).draw();

            $('#modalEditardevolucion').modal("hide");
        }


    });

    $(document).on('click', '#cancelar', function (e) {
      var subtotal = 0;
           subtotal =  parseFloat($('#precio').val()) * parseFloat($('#cantidad1').val())
        detalles_table.row.add({
            'id': $('#id_edit').val(),
            'producto': $('#producto').val(),
            'cantidad': $('#cantidad1').val(),
            'precio': $('#precio').val(),
            'subtotal': subtotal,
            'idproducto': $('#idproducto').val(),
            'restar': 0,
              'posicion': contador,
                'maestro': $('#maestro').val()
        }).draw();
            $('#modalEditardevolucion').modal("hide");
    });

    $(document).on('click', '#cerrar', function (e) {
      var subtotal = 0;
           subtotal =  parseFloat($('#precio').val()) * parseFloat($('#cantidad1').val())
        detalles_table.row.add({
            'id': $('#id_edit').val(),
            'producto': $('#producto').val(),
            'cantidad': $('#cantidad1').val(),
            'precio': $('#precio').val(),
            'subtotal': subtotal,
            'idproducto': $('#idproducto').val(),
            'restar': 0,
              'posicion': contador,
                'maestro': $('#maestro').val(),
        }).draw();
            $('#modalEditardevolucion').modal("hide");
    });

    $('#modalEditardevolucion').on('hide.bs.modal', function(){
        $("#modalEditardevolucion").validate().resetForm();
        document.getElementById("EditarForm").reset();
        window.location.hash = '#';
    });

    $('#modalEditardevolucion').on('shown.bs.modal', function(){
        window.location.hash = '#editar';
    });


    </script>
    <script src="{{asset('js/devoluciones/modaleditar.js')}}"></script>{{-- validator --}}
    <script src="{{asset('js/bodegas/edit.js')}}"></script>
    <script src="{{asset('js/pedidos/showEdit.js')}}"></script>{{-- validator --}}
@endpush
