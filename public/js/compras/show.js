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
        "width": "15%",
        "responsivePriority": 2,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },
    
    {
        "title": "Cantidad",
        "data": "cantidad",
        "width": "10%",
        "responsivePriority": 4,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },
    
    {
        "title": "Precio de Compra",
        "data": "precio_compra",
        "width": "10%",
        "responsivePriority": 1,
        "className": "text-right",
        "render": function (data, type, full, meta) {
            return 'Q. ' + (data);
        },
    },
    
    {
        "title": "Fecha de Caducidad",
        "data": "caducidad",
        "width": "10%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Fecha de Ingreso",
        "data": "fecha_ingreso",
        "width": "10%",
        "responsivePriority": 2,
        "render": function (data, type, full, meta) {
            return (data);
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

            if (full.existencias < full.cantidad) {
                return " ";
            }else{
                return "<div id='" + full.id + "' class='text-center'>" +
                "<div class='float-right col-lg-12'>" +
                "<a href='#' class='remove-detalle' data-method='delete' data-id='" + full.id + "' data-target='#modalConfirmarAccion' data-toggle='modal'>" +
                "<i class='fas fa-trash-alt' title='Eliminar Detalle'></i>" +
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
        url: APP_URL + "/compras/" + id + "/deleteDetalle",
        data: formData,
        dataType: "json",
        success: function (data) {
            if (data.back == 'true') {
                $('.loader').fadeOut(225);
                $('#modalConfirmarAccion').modal("hide");
                detalles_table.ajax.reload();
                alertify.set('notifier', 'position', 'top-center');
                alertify.success('El detalle fue eliminado exitosamente');
                window.location.assign('/compras?lastDetail'); 
            }else{
                $('.loader').fadeOut(225);
                $('#modalConfirmarAccion').modal("hide");
                detalles_table.ajax.reload();
                alertify.set('notifier', 'position', 'top-center');
                alertify.success('El detalle fue eliminado exitosamente');
                window.location.reload(true); 
            }
        },
        error: function (errors) {
            $('.loader').fadeOut(225);
            alertify.set('notifier', 'position', 'top-center');
            alertify.error('No se pudo eliminar el detalle');
        }

    });
}


