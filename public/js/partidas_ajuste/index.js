var partidas_ajuste_table = $('#partidas-ajuste-table').DataTable({
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
            title: 'partidas_ajuste',
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
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
        "title": "Códiga Partida",
        "data": "codigo_partida",
        "width": "17%",
        "responsivePriority": 2,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Fecha",
        "data": "fecha_ingreso",
        "width": "17%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Total Ingreso",
        "data": "total_ingreso",
        "width": "17%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Total Salida",
        "data": "total_salida",
        "width": "17%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data.split(' ')[0]);
        },
    },

    {
        "title": "Saldo",
        "data": "saldo",
        "width": "17%",
        "responsivePriority": 4,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },
    {
        "visible": false,
        "title": "No.",
        "data": "id",
        "width": "0%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },
    {
        "title": "Acciones",
        "orderable": false,
        "width": "15%",
        "className": "text-center",
        "render": function (data, type, full, meta) {
            var rol_user = $("input[name='rol_user']").val();
            var urlActual = $("#urlActual").val();

            return "<div id='" + full.id + "' class='text-center'>" +
                "<div class='float-left col-lg-12'>" +
                "<a class='show-partida-ajuste' href='" + urlActual + "/" + full.id + "' >" +
                "<i class='fas fa-info-circle' title='Ver detalles de partida'></i>" +
                "</a>" + "</div>" +
                "</div>";
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
        url: APP_URL + "/notas_envio/" + id + "/delete",
        data: formData,
        dataType: "json",
        success: function (data) {
            $('.loader').fadeOut(225);
            $('#modalConfirmarAccion').modal("hide");
            notas_envio_table.ajax.reload();
            alertify.set('notifier', 'position', 'top-center');
            alertify.success('La Nota de envío se Desactivó Correctamente!!');
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
// Get property value by key/nested key path
Object.byString = function (o, s) {
    s = s.replace(/\[(\w+)\]/g, '.$1'); // convert indexes to properties
    s = s.replace(/^\./, '');           // strip a leading dot
    var a = s.split('.');
    for (var i = 0, n = a.length; i < n; ++i) {
        var k = a[i];
        if (k in o) {
            o = o[k];
        } else {
            return;
        }
    }
    return o;
}

// Table body builder
function buildTableBody(data, columns, showHeaders, headers) {
    var body = [];
    // Inserting headers
    if (showHeaders) {
        body.push(headers);
    }

    // Inserting items from external data array
    data.forEach(function (row) {
        var dataRow = [];
        var i = 0;

        columns.forEach(function (column) {
            dataRow.push({ text: Object.byString(row, column), alignment: headers[i].alignmentChild, fontSize: hearders[i].fontSizeChild });
            i++;
        })
        body.push(dataRow);

    });

    return body;
}

// returns a pdfmake table
function table(data, columns, witdhsDef, showHeaders, headers, layoutDef) {
    return {
        table: {
            headerRows: 1,
            widths: witdhsDef,
            body: buildTableBody(data, columns, showHeaders, headers)
        },
        layout: {
            fillColor: function (rowIndex, node, columnIndex) {
                return (rowIndex % 2 !== 0) ? '#eee' : null;
            },
            hLineWidth: function (i, node) {
                return (i === 0 || i === node.table.body.length) ? 0 : 1;
            },
            vLineWidth: function (i, node) {
                return 0;
            },
            hLineColor: function (i, node) {
                return 'gray';
            },
        }
    };
}
