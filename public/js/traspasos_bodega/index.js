var traspasos_bodega_table = $('#traspasos-bodega-table').DataTable({
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
            title: 'Productos',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
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

    "columns": [
        
        {
            "title": "Fecha",
            "data": "created_at",
            "width": "20%",
            "responsivePriority": 1,
            "render": function (data, type, full, meta) {
                return (data);
            },
        },
        {
            "title": "Bodega de Origen",
            "data": "bodega_origen",
            "width": "20%",
            "responsivePriority": 1,
            "render": function (data, type, full, meta) {
                return (data);
            },
        },
        
        {
            "title": "Bodega de Destino",
            "data": "bodega_destino",
            "width": "20%",
            "responsivePriority": 4,
            "render": function (data, type, full, meta) {
                return (data);
            },
        },
        
        {
            "title": "Producto",
            "data": "producto",
            "width": "20%",
            "responsivePriority": 2,
            "render": function (data, type, full, meta) {
                return (data);
            },
        },
        
        {
        "title": "Cantidad",
        "data": "cantidad",
        "width": "20%",
        "responsivePriority": 2,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },
    {
        "title": "Usuario",
        "data": "user",
        "width": "20%",
        "responsivePriority": 2,
        "className": "text-right",
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    // {
    //     "title": "Acciones",
    //     "orderable": false,
    //     "width": "10%",
    //     "class": "text-center",
    //     "render": function (data, type, full, meta) {
    //         var rol_user = $("input[name='rol_user']").val();
    //         var urlActual = $("input[name='urlActual']").val();

    //         return "<div id='" + full.id + "' class='text-center'>" +
    //             "<div class='float-left col-lg-12'>" +
    //             "<a href='" + urlActual + "/" + full.id + "' class='edit-compra' >" +
    //             "<i class='fas fa-info-circle' title='Ver detalles de Traspaso'></i>" +
    //             "</a>" + "</div>" +
    //             "</div>";


    //         }
    //     ,
    //     "responsivePriority": 5
    // }
]

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
        url: APP_URL + "/productos/" + id + "/delete",
        data: formData,
        dataType: "json",
        success: function (data) {
            $('.loader').fadeOut(225);
            $('#modalConfirmarAccion').modal("hide");
            productos_table.ajax.reload();
            alertify.set('notifier', 'position', 'top-center');
            alertify.success('El Producto se Desactivó Correctamente!!');
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

$(document).on('click', 'a.activar-producto', function (e) {
    e.preventDefault(); // does not go through with the link.

    var $this = $(this);
    alertify.confirm('Activar Producto', 'Esta seguro de activar el producto',
        function () {
            $('.loader').fadeIn();
            $.post({
                type: $this.data('method'),
                url: $this.attr('href')
            }).done(function (data) {
                $('.loader').fadeOut(225);
                productos_table.ajax.reload();
                alertify.set('notifier', 'position', 'top-center');
                alertify.success('Producto Activado con Éxito!!');
            });
        }
        , function () {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error('Cancelar')
        });
});

