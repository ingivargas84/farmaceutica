var detalle_table = $('#detalle-table').DataTable({
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
        "sEmptyTable": "Ningún detalle agregado a esta compra.",
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
            "title": "ID",
            "data": "id_producto",
            "width": "15%",
            "className": "text-center",
            "visible": false,
            "responsivePriority": 2,
            "render": function (data, type, full, meta) {
                return (data);
            },
        },
        {
            "title": "tipo_ajuste",
            "data": "tipo_ajuste",
            "width": "15%",
            "className": "text-center",
            "visible": false,
            "responsivePriority": 2,
            "render": function (data, type, full, meta) {
                return (data);
            },
        },

        {
            "title": "Producto",
            "data": "nombre_producto",
            "width": "18%",
            "className": "text-center",
            "responsivePriority": 2,
            "render": function (data, type, full, meta) {
                return (data);
            },
        },

        {
            "title": "Cantidad",
            "data": "cantidad",
            "width": "18%",
            "className": "text-center",
            "responsivePriority": 1,
            "render": function (data, type, full, meta) {
                return (data);
            },
        },

        {
            "title": "Precio",
            "data": "precio",
            "width": "18%",
            "className": "text-right",
            "responsivePriority": 4,
            "render": function (data, type, full, meta) {
                return 'Q. ' + (data);
            },
        },

        {
            "title": "Ingreso",
            "data": "ingreso",
            "width": "18%",
            "responsivePriority": 2,
            "className": "text-right sub",
            "render": function (data, type, full, meta) {
                return 'Q. ' + (data);
            },
        },

        {
            "title": "Salida",
            "data": "salida",
            "width": "18%",
            "responsivePriority": 2,
            "className": "text-right sub",
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
                return "<div class='float-right col-lg-12'>" +
                    "<a href='#' class='remove-detail' data-method='delete' data-id='" + full.id + "'>" +
                    "<i class='fas fa-trash-alt' title='Eliminar Compra'></i>" +
                    "</a>" + "</div></div>";
            },
            "responsivePriority": 5
        }]

});

$(document).on('click', 'a.remove-detail', function (e) {
    e.preventDefault(); // does not go through with the link.
    var $this = $(this);
    alertify.confirm('Eliminar Detalle', 'Esta seguro de eliminar el detalle',
        function () {
            $('.loader').fadeIn();
            //removes the table row
            detalle_table.row($this.parents('tr')).remove().draw();

            //recalculates total after deletion
            if (!detalle_table.data().count()) {
                $('#total').val(null);
            } else {
                //adds all ingreso row data and sets the total_ingreso input
                var total_ingreso = 0;
                detalle_table.column(5).data().each(function (value, index) {
                    total_ingreso = total_ingreso + parseFloat(value);
                    parseFloat(total_ingreso).toFixed(2);
                    $('#total_i').val(total_ingreso);
                    $('#total_i-error').remove();
                });

                //adds all salida row data and sets the total_salida input
                var total_salida = 0;
                detalle_table.column(6).data().each(function (value, index) {
                    total_salida = total_salida + parseFloat(value);
                    parseFloat(total_salida).toFixed(2);
                    $('#total_s').val(total_salida);
                    $('#total_s-error').remove();
                });

                var saldo = parseFloat(total_ingreso - total_salida).toFixed(2);
                $('#saldo').val(saldo);

            }
        }
        , function () {
            alertify.set('notifier', 'position', 'top-center');
            alertify.warning('Cancelar')
        });
});

