var compras_table = $('#compras-table').DataTable({
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
            title: 'Compras',
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
        "sEmptyTable": "Ning煤n dato disponible en esta tabla",
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
            "sLast": "脷ltimo",
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
        "title": "Fecha",
        "data": "fecha",
        "width": "10%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Proveedor",
        "data": "proveedor",
        "width": "10%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "No. de Factura",
        "data": "noFactura",
        "width": "15%",
        "responsivePriority": 4,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Total",
        "data": "total",
        "width": "10%",
        "responsivePriority": 2,
        "className": "text-right",
        "render": function (data, type, full, meta) {
            return 'Q. ' + (data);
        },
    },

    {
        "title": "Acciones",
        "orderable": false,
        "width": "10%",
        "className": "text-center",
        "render": function (data, type, full, meta) {
            var rol_user = $("input[name='rol_user']").val();
            var urlActual = $("input[name='urlActual']").val();

            if(full.existencias < full.cantidad){
                return "<div id='" + full.id + "' class='text-center'>" +
                    "<div class='float-left col-lg-12'>" +
                    "<a href='" + urlActual + "/" + full.id + "' class='edit-compra' >" +
                    "<i class='fas fa-info-circle' title='Ver detalles de Compra'></i>" +
                    "</a>" + "</div>" +
                    "</div>";
            }else{
                return "<div id='" + full.id + "' class='text-center'>" +
                    "<div class='float-left col-lg-6'>" +
                    "<a href='" + urlActual + "/" + full.id + "' class='edit-compra' >" +
                    "<i class='fas fa-info-circle' title='Ver detalles de Compra'></i>" +
                    "</a>" + "</div>" +
                    "<div class='float-right col-lg-6'>" +
                    "<a href='#' class='remove-compra' data-method='delete' data-id='" + full.id + "'   data-target='#modalConfirmarAccion' data-toggle='modal'>" +
                    "<i class='fas fa-trash-alt' title='Eliminar Compra'></i>" +
                    "</a>" + "</div></div>";
            }
        },
        "responsivePriority": 5
    }]

});

$("#btnConfirmarAccion").click(function (event) {
    event.preventDefault();
    confirmarAccion();
});


function confirmarAccion(button) {
    $('.loader').fadeIn();
    var formData = $("#ConfirmarAccionForm").serialize();
    var id = $("#idConfirmacion").val();
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        url: APP_URL + "/compras/" + id + "/delete",
        data: formData,
        dataType: "json",
        success: function (data) {
            $('.loader').fadeOut(225);
            $('#modalConfirmarAccion').modal("hide");
            compras_table.ajax.reload();
            alertify.set('notifier', 'position', 'top-center');
            alertify.success('La Compra se Desactiv贸 Correctamente!!');
        },
        error: function (errors) {
            $('.loader').fadeOut(225);
            if (errors.responseText != "") {
                var errors = JSON.parse(errors.responseText);
                if (errors.password_actual != null) {
                    $("input[name='password_actual'] ").after("<label class='error' id='ErrorPassword_actual'>" + errors.password_actual + "</label>");
                }
                else {
                    $("#ErrorPassword_actual").remove();
                }
            }

        }

    });
}

$(document).on('click', 'a.activar-compra', function (e) {
    e.preventDefault(); // does not go through with the link.

    var $this = $(this);
    alertify.confirm('Activar Compra', 'Esta seguro de activar la compra',
        function () {
            $('.loader').fadeIn();
            $.post({
                type: $this.data('method'),
                url: $this.attr('href')
            }).done(function (data) {
                $('.loader').fadeOut(225);
                compras_table.ajax.reload();
                alertify.set('notifier', 'position', 'top-center');
                alertify.success('Compra Activada con 脡xito!!');
            });
        }
        , function () {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error('Cancelar')
        });
});

