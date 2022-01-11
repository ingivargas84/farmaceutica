var formas_pago_table = $('#formas-pago-table').DataTable({
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
            title: 'Formas de Pago',
            exportOptions: {
                columns: [0]
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
        "title": "Forma de Pago",
        "data": "nombre",
        "width": "80%",
        "responsivePriority": 2,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Acciones",
        "orderable": false,
        "width": "15%",
        "render": function (data, type, full, meta) {
            var rol_user = $("input[name='rol_user']").val();
            var urlActual = $("input[name='urlActual']").val();

            if (full.estado == 1) {
                return "<div id='" + full.id + "' class='text-center' >" +
                    "<div class='float-left col-lg-4'>" +
                    "<a id='modal_edit' class='edit-forma-pago'>" +
                    "<i class='fa fa-btn fa-edit' title='Editar forma de pago'></i>" +
                    "</a>" + "</div>" +
                    "<div class='float-right col-lg-4'>" +
                    "<a href='#' class='remove-forma-pago' data-method='delete' data-id='" + full.id + "' data-target='#modalConfirmarAccion' data-toggle='modal'>" +
                    "<i class='fa fa-thumbs-down' title='Eliminar forma de pago'></i>" +
                    "</a>" + "</div>";

            } else {
                if (rol_user == 'Super-Administrador' || rol_user == 'Administrador') {
                    return "<div id='" + full.id + "' class='text-center'>" +
                        "<div class='float-right col-lg-6'>" +
                        "<a href='" + urlActual + "/" + full.id + "/activar' class='activar-forma-pago'" + "data-method='post' data-id='" + full.id + "' >" +
                        "<i class='fa fa-thumbs-up' title='Activar Forma de Pago'></i>" +
                        "</a>" + "</div>";
                } else {
                    return "<div id='" + full.id + "' class='text-center'>" + "</div>";
                }

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
        url: APP_URL + "/formas_pago/" + id + "/delete",
        data: formData,
        dataType: "json",
        success: function (data) {
            $('.loader').fadeOut(225);
            $('#modalConfirmarAccion').modal("hide");
            formas_pago_table.ajax.reload();
            alertify.set('notifier', 'position', 'top-center');
            alertify.success('La Forma de Pago se Eliminó Correctamente!!');
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

$(document).on('click', 'a.activar-forma-pago', function (e) {
    e.preventDefault(); // does not go through with the link.

    var $this = $(this);
    alertify.confirm('Activar Forma de Pago', 'Esta seguro de activar la forma de pago',
        function () {
            $('.loader').fadeIn();
            $.post({
                type: $this.data('method'),
                url: $this.attr('href')
            }).done(function (data) {
                $('.loader').fadeOut(225);
                formas_pago_table.ajax.reload();
                alertify.set('notifier', 'position', 'top-center');
                alertify.success('Forma de Pago Activada con Éxito!!');
            });
        }
        , function () {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error('Cancelar')
        });
});

