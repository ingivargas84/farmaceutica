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
            if (full.restar == 0 && full.posicion == 0 && full.cantidad < 0) {
              return "sin acciones"
            }else {
              if (full.restar >= 0) {
                return "<div id='" + full.id + "' cantidad='" + full.cantidad + "' restar='" + full.restar + "' posicion='" + full.posicion + "' maestro='" + full.maestro + "' class='text-center'>"+
                        "<div class='float-left col-lg-4'>" +
                        "<a id='modalEditardevolucion' class='edit-devolucion'>" +
                        "<i class='fa fa-btn fa-edit' title='Editar Detalle'></i>" +
                        "</a>" + "</div>" ;
              }else {
                return "<div id='" + full.id + "' cantidad='" + full.cantidad + "' restar='" + full.restar + "' posicion='" + full.posicion + "'  maestro='" + full.maestro + "' class='text-center'>"+
                        "<div class='float-left col-lg-4'>" +
                        "<a id='modalEditardevolucion' class='delete-devolucion'>" +
                        "<i class='fa fa-thumbs-down' title='eliminar Detalle'></i>" +
                        "</a>" + "</div>" ;
              }
            }



        },
        "responsivePriority": 5
    }]
});
$(document).on('click', 'a.edit-devolucion', function (e) {
    e.preventDefault(); // does not go through with the link.
    var $this = $(this);
    $('.loader').fadeIn();
    //removes the table row
    detalles_table.row($this.parents('tr')).remove().draw();

});

$(document).on('click', 'a.delete-devolucion', function (e) {
    e.preventDefault(); // does not go through with the link.
    var $this = $(this);
    $('.loader').fadeIn();
    //removes the table row
    detalles_table.row($this.parents('tr')).remove().draw();


});
