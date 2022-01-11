var cuentas_pagar_table = $('#cuentas_pagar_table').DataTable({
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

    "columns": [
    {
        "title": "Proveedor",
        "data": "proveedor",
        "width": "17%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Saldo",
        "data": "saldo",
        "width": "17%",
        "responsivePriority": 1,
        "className": "text-right",
        "render": function (data, type, full, meta) {
            return "Q. " + (data);
        },
    },
    {
        "title": "Fecha",
        "data": "fecha",
        "width": "17%",
        "responsivePriority": 2,
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
                "<div class='float-left col-lg-6'>" +
                "<a href='#' id='report' >" +
                "<i class='fas fa-file-download' title='Descargar reporte de cuenta'></i>" +
                "</a>" + "</div>" +
                "<div class='float-left col-lg-6'>" +
                "<a href='" + urlActual + "/" + full.id + "' class='edit-pedido' >" +
                "<i class='fas fa-info-circle' title='Ver detalles del Pedido'></i>" +
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
        url: APP_URL + "/pedidos/" + id + "/delete",
        data: formData,
        dataType: "json",
        success: function (data) {
            $('.loader').fadeOut(225);
            $('#modalConfirmarAccion').modal("hide");
            pedidos_table.ajax.reload();
            alertify.set('notifier', 'position', 'top-center');
            alertify.success('El Pedido se Desactivó Correctamente!!');
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
            dataRow.push({ text: Object.byString(row, column), alignment: headers[i].alignmentChild });
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
    var url = $('#urlActual').val() + "/" + id + "/getDetalles";
    var row_data = cuentas_pagar_table.row($(this).closest('tr')).data();
    
    $.ajax({
        type: "GET",
        headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        url: url,
        dataType: "json",
        success: function (data) {
            var details = data;
            var details = JSON.stringify(details);
            var details = JSON.parse(details);

            //give the data a little treatment and formatting
            for (let i = 0; i < details['data'].length; i++) {
                if (details['data'][i].tipo == "Compra") {
                    details['data'][i].monto = "+ Q." + parseFloat(details['data'][i].monto).toFixed(2);
                }else{
                    details['data'][i].monto = "-  Q." + parseFloat(details['data'][i].monto).toFixed(2);
                }
                //adds a meaningless no_compra value in case of null
                if (details['data'][i].no_compra  == null) {
                    details['data'][i].no_compra = '----'
                }
                //adds a meaningless forma_pago value in case it doesn't have one
                if (!details['data'][i].forma_pago) {
                    details['data'][i].forma_pago = '----'
                }
            }

            var options = { year: 'numeric', month: 'short', day: 'numeric' };
            var today = new Date();
            var dd = {
                content: [
                    {//invoice header
                        columns: [
                            {
                                text: [
                                    { text: 'DISTRIBUIDORA EL ANGEL\n', style: 'titulo' },
                                    { text: '4 Avenida A, 6C-23 APTO. 33, Zona 9 Cerezos 1 \n Quetzaltenango, Quetzaltenango \n NIT: 5297559', style: 'about' }
                                ]
                            },
                            {
                                image: "data:image/jpeg;base64,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",
                                width: 110,
                                alignment: 'center'
                            },

                        ]
                    },
                    { canvas: [{ type: 'line', x1: 0, y1: 5, x2: 595 - 2 * 40, y2: 5, lineWidth: 0.5 }] },//hr
                    { text: "\n" },
                    { text: "Reporte de Cuenta al " + today.toLocaleDateString("es-ES", options) , style: 'titulo' },
                    { text: "\n" },
                    {
                        columns: [
                            {
                                text: [
                                    {text: 'Creación de la Cuenta', style: 'subtitulo'},
                                ]
                            },
                            {
                                text:[
                                    {text: 'Proveedor:', style: 'subtitulo', alignment:'center'},
                                ]
                            },
                            {
                                text:[
                                    { text: 'Saldo', style: 'subtitulo', alignment: 'right'},
                                ]
                            },
                        ]
                    },
                    {
                        columns: [
                            {
                                text: [
                                    {text: row_data.fecha}
                                ]
                            },
                            {
                                text:[
                                    { text: row_data.proveedor, alignment: 'center'}
                                ]
                            },
                            {
                                text:[
                                    { text: 'Q. ' + row_data.saldo, alignment: 'right'}
                                ]
                            },
                        ]
                    },
                    { text: "\n" },
                    { canvas: [{ type: 'line', x1: 0, y1: 5, x2: 595 - 2 * 40, y2: 5, lineWidth: 0.5 }] },//hr
                    { text: "\n" },
                    { text: "Detalle", style: 'titulo' },
                    { text: "\n" },
                    table(
                        details['data'],
                        ['fecha_ingreso', 'tipo', 'no_compra', 'forma_pago', 'monto'],
                        ['20%', '20%', '20%', '20%', '20%'],
                        true,
                        [{ text: 'Fecha', bold: 'true', },
                        { text: 'Transacción', bold: 'true', alignmentChild: 'center', alignment: 'center' },
                        { text: 'No. Compra/Documento', bold: 'true', alignmentChild: 'center', alignment: 'center' },
                        { text: 'Forma de Pago', bold: 'true', alignmentChild: 'center', alignment: 'center' },
                        { text: 'Monto', bold: 'true', alignmentChild: 'right', alignment: 'right' }],
                        ''
                    ),
                    { text: "\n" },
                    {
                        table: {
                            widths: ['25%', '25%', '25%', '25%'],
                            body: [
                                [
                                    '',
                                    '',
                                    { text: 'Saldo', bold: true, alignment: 'right' },
                                    { text: 'Q.' + row_data.saldo, alignment: 'right' }
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
                            },
                        }
                    }
                ],
                styles: {
                    titulo: {
                        fontSize: 18,
                        bold: true
                    },
                    about: {
                        fontSize: 11,
                    },
                    subtitulo: {
                        fontSize: 13,
                        bold: true
                    }
                }
            };

            pdfMake.createPdf(dd).download('El Angel - Cuenta' + ' ' + row_data.proveedor);
        },
        error: function () {

        }
    });

})
