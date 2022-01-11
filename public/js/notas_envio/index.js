var notas_envio_table = $('#notas-envio-table').DataTable({
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
            title: 'Pedidos',
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
        "title": "No. de Nota de Envío",
        "data": "no_nota",
        "width": "17%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "No. de Pedido",
        "data": "no_pedido",
        "width": "17%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Cliente",
        "data": "cliente",
        "width": "17%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Creación",
        "data": "created_at",
        "width": "17%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data.split(' ')[0]);
        },
    },

    {
        "title": "Estado",
        "data": "estado_nota",
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
        "visible": false,
        "title": "tel",
        "data": "telefono",
        "width": "0%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },
    {
        "visible": false,
        "title": "dir",
        "data": "direccion",
        "width": "0%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },
    {
        "visible": false,
        "title": "total",
        "data": "total",
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

            return "<div id='" + full.id_m + "' class='text-center'>" +
                "<div class='float-left col-lg-4'>" +
                "<a href='#' id='report' >" +
                "<i class='fas fa-file-download' title='Descargar nota de envío'></i>" +
                "</a>" + "</div>" +
                "<div class='float-left col-lg-4'>" +
                "<a class='edit-nota-envio' >" +
                "<i class='fas fa-edit' title='Editar nota de envío'></i>" +
                "</a>" + "</div>" +
                "<div class='float-right col-lg-4'>" +
                "<a href='#' class='remove-nota-envio' data-method='delete' data-id='" + full.id + "' data-target='#modalConfirmarAccion' data-toggle='modal'>" +
                "<i class='fas fa-trash-alt' title='Eliminar Nota de Envío'></i>" +
                "</a>" + "</div></div>";
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

//generate PDF report
$(document).on('click', '#report', function () {
    var id = $(this).parent().parent().attr('id')
    console.log(id);
    var url = 'http://' + window.location.host + "/pedidos/" + id + "/getDetalles/";
    var row_data = notas_envio_table.row($(this).closest('tr')).data();

    $.ajax({
        type: "GET",
        headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        url: url,
        dataType: "json",
        success: function (data) {
            var details = data;
            var details = JSON.stringify(details);
            var details = JSON.parse(details);
            for (let i = 0; i < details['data'].length; i++) {
                details['data'][i].precio = "Q." + parseFloat(details['data'][i].precio).toFixed(2);
                details['data'][i].subtotal = "Q." + parseFloat(details['data'][i].subtotal).toFixed(2);
            }
            
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            var date = new Date(row_data.created_at);
            var dd = {
                pageSize: "LETTER",
                pageMargin: [ 20, 0, 20, 40 ],
                pageOrientation: 'portrait',
                content: [
                    {//invoice header
                        columns: [
                            {
                                text: [
                                    { text: 'DISTRIBUIDORA EL ANGEL\n\n', style: 'titulo', alignment: 'center' },
                                ]
                            },
                        ]
                    },
                    {
                        columns: [
                            { text: 'Nota de envío', style: 'subtitulo', italics: 'true', width: '45%' },
                            { text: row_data.no_nota, style: 'subtitulo', italics: 'true' },
                        ]
                    },
                    { text: "\n" },
                    {
                        columns: [
                            {
                                text: 'Fecha:',
                                width: '20%',
                                lineHeight: 1
                            },
                            {
                                text: date.toLocaleDateString("es-ES", options)
                            },
                        ]
                    },
                    {
                        columns: [
                            {
                                text: 'Nombre:',
                                width: '20%',
                                lineHeight: 1
                            },
                            {
                                text: row_data.cliente,
                            },
                        ]
                    },
                    {
                        columns: [
                            {
                                text: 'Dirección:',
                                width: '20%',
                                lineHeight: 1
                            },
                            {
                                text: row_data.direccion,
                            },
                        ]
                    },
                    {
                        columns: [
                            {
                                text: 'Teléfono:',
                                width: '20%',
                                lineHeight: 1
                            },
                            {
                                text: row_data.telefono,
                            },
                        ]
                    },
                    { text: "\n" },
                    table(
                            details['data'],
                            ['cantidad', 'producto', 'precio', 'subtotal'],
                            ['16%', '59%', '10%', '15%'],
                            true,
                            [
                                { text: 'Cantidad', bold: 'true', alignment: 'center', fontSizeChild: 10, alignmentChild: 'center'},
                                { text: 'Producto', bold: 'true', fontSizeChild: 10 },
                                { text: 'Precio', bold: 'true', fontSizeChild: 10, alignmentChild: 'right', alignment: 'right' },
                                { text: 'Subtotal', bold: 'true', fontSizeChild: 10, alignmentChild: 'right', alignment: 'right' }
                            ],
                            ''
                        ),
                        { text: "\n" },
                        {
                            table: {
                                widths: ['16%', '59%', '10%', '15%'],
                                body: [
                                    [
                                        { text: 'Total en letras:', bold: true},
                                        { text: numeroALetras(row_data.total), fontSize: 11 },
                                        { text: 'Total', bold: true, alignment: 'right' },
                                        { text: 'Q.' + parseFloat(row_data.total).toFixed(2), alignment: 'right' }
                                    ],
                                ]
                            },
                            layout: {
                                fillColor: function (rowIndex, node, columnIndex) {
                                    return '#ccc'
                                },
                                hLineWidth: function (i, node) {
                                    return (i === 0 || i === node.table.body.length) ? 0 : 1;
                                },
                                vLineWidth: function (i, node) {
                                    return 0;
                                },
                                hLineColor: function (i, node) {
                                    return 'gray';
                                }
                            }
                        },
                        
                    ],
                styles: {
                    titulo: {
                        fontSize: 18,
                        bold: true
                    },
                    about: {
                        fontSize: 10,
                    },
                    subtitulo: {
                        fontSize: 13,
                        bold: true
                    }
                }
            }

            pdfMake.createPdf(dd).open();
        },
        error: function () {

        }
    });

})
