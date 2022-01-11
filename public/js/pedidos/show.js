var detalles_table = $('#detalles-table').DataTable({
    "responsive": true,
    "processing": true,
    "info": true,
    "showNEntries": true,
    "dom": 'Bfrtip',

    lengthMenu: [
        [10, 25, 50, -1],
        ['10 filas', '25 filas', '50 filas', 'Mostrar todo']
    ],

    "buttons": [
        'pageLength',
        {
            extend: 'excelHtml5',
            title: 'Detalles',
            exportOptions: {
                columns: [0, 1, 2, 3]
            }
        }
    ],

    "paging": true,
    "language": {
        "sdecimal": ".",
        "sthousands": ",",
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
    },
    "order": [0, 'desc'],

    "columns": [{
        "title": "Producto",
        "data": "producto",
        "width": "22%",
        "responsivePriority": 2,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Cantidad",
        "data": "cantidad",
        "width": "22%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Precio",
        "data": "precio",
        "width": "22%",
        "className": "text-right",
        "responsivePriority": 4,
        "render": function (data, type, full, meta) {
            return "Q. " + Number.parseFloat((data)).toFixed(2);
        },
    },

    {
        "title": "Subtotal",
        "data": "subtotal",
        "width": "22%",
        "responsivePriority": 2,
        "className": "text-right",
        "render": function (data, type, full, meta) {
            return 'Q. ' + Number.parseFloat((data)).toFixed(2);
        },
    },

    {

        "title": "Acciones",
        "orderable": false,
        "width": "12%",
        "className": "text-center",
        "render": function (data, type, full, meta) {
            var rol_user = $("input[name='rol_user']").val();
            var urlActual = $("input[name='urlActual']").val();
            if (full.estado != null){
              if (a == 1) {
            return "<div id='" + full.id + "' class='text-center'>"+
                    "<div class='float-left col-lg-4'>" +
                    "<a id='modalEditar' class='edit-bodega'>" +
                    "<i class='fa fa-btn fa-edit' title='Editar Detalle'></i>" +
                    "</a>" + "</div>"+
                  "<div class='float-right col-lg-6'>" +
                  "<a href='#' class='remove-detalle'>" +
                  "<i class='fas fa-trash-alt' title='Eliminar Detalle' id='destroy-detalle'></i>" +
                  "</a>" + "</div></div>" ;
            }else {
              return  "sin acciones";
            }
          }else {
            return "sin acciones";
          }
        },
        "responsivePriority": 5
    }]

});

$("#btnConfirmarAccion").click(function (event) {
    event.preventDefault();
    confirmarAccion();
});



$(document).on('click', '#destroy-detalle', function(){
    var id = $(this).parent().parent().parent().attr('id');
    var url = id + "/deleteDetalle"
    console.log(url);

    alertify.confirm('Eliminar el detalle', "¿Está seguro que desea eliminar el detalle? \n Esta operación es irreversible",
        function () {
            $.ajax({
                type: "POST",
                headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
                url: url,
                dataType: "json",
                success: function(data){
                    if (data.back == 'true') {
                        detalles_table.ajax.reload();
                        alertify.set('notifier', 'position', 'top-center');
                        window.location.assign('/pedidos?lastDetail');
                    } else {
                        detalles_table.ajax.reload();
                        alertify.set('notifier', 'position', 'top-center');
                        alertify.success('El detalle fue eliminado exitosamente');
                        window.location.reload(true);
                    }
                },
                error: function(data){
                    alertify.set('notifier', 'position', 'top-center');
                    alertify.danger('Ocurrió un error al eliminar el detalle.')
                },
            })
        },
        function () {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error('Operación cancelada');
        })

});


$(document).on('click', 'a.remove-detail', function (e) {
    e.preventDefault(); // does not go through with the link.
    var $this = $(this);
    alertify.confirm('Eliminar Detalle', 'Esta seguro de eliminar el detalle',
        function () {
            $('.loader').fadeIn();
            //removes the table row
            detalles_table.row($this.parents('tr')).remove().draw();
            datos.push({
                'id': "",
                'Eliminar': $this.data("id"),
            });
            //recalculates total after deletion
            if (!detalles_table.data().count()) {
                $('#total').val(null);
                $('#descuento').val(null);
            }else{
                var total = 0;
                detalles_table.column(3).data().each(function (value, index) {
                    total = total + parseFloat(value);
                    $('#total').val(total);

                });


            }
        }
        , function () {
            alertify.set('notifier', 'position', 'top-center');
            alertify.warning('Cancelar')
        });
});
