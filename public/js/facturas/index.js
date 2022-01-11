var facturas_table = $('#facturas-table').DataTable({
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
            title: 'Facturas',
            exportOptions: {
                columns: [0, 1, 2]
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
        "title": "Serie-Número Factura",
        "width": "22%",
        "responsivePriority": 2,
        "render": function (data, type, full, meta) {
            return (full.serie_factura+'-'+full.no_factura)
        },
    },

    {
        "title": "Cliente",
        "width": "22%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            if (full.cliente_factura == null) {
              return (full.cliente)
            }else {
              return (full.cliente_factura)
            }
        },
    },

    {
        "title": "Fecha",
        "data": "fecha",
        "width": "22%",
        "responsivePriority": 4,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Total",
        "data": "total",
        "width": "22%",
        "responsivePriority": 4,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },
    {
        "title": "Estado",
        "width": "22%",
        "responsivePriority": 4,
        "render": function (data, type, full, meta) {

          if (full.estado == 1)
          {
            return ("Creada");
          }else {
                        return ("Anulada");
                      }
        },
    },

    {
        "title": "Acciones",
        "orderable": false,
        "width": "12%",
        "className": 'text-center',
        "render": function (data, type, full, meta) {
            var rol_user = $("input[name='rol_user']").val();
            var urlActual = $("#urlActual").val();

            if (full.estado == 1) {
                return "<div id='" + full.id + "' class='text-center' >" +

                    "<div class='float-right col-lg-4'>" +
                    "<a href='#' class='remove-factura' data-method='delete' data-id='" + full.id + "' data-target='#modalConfirmarAccion' data-toggle='modal'>" +
                    "<i class='fa fa-minus-circle' title='Anular Factura'></i>" +
                    "</a>" + "</div>"+

                    "<div class='float-left col-lg-4'>" +
                    "<a href='" + urlActual + "/" + full.id + "' class='edit-factura' >" +
                    "<i class='fas fa-info-circle' title='Ver detalles de Factura'></i>" +
                    "</a>" + "</div>" +

                    "<div class='float-left col-lg-4' >" +
                    "<a href='" + urlActual + "/NuevaFactura/" + full.id + "' class='imprimir-factura1'  id='" + full.id + "'>" +
                    "<i class='fas fa-print' title='Imprimir Factura'></i>" +
                    "</a>" + "</div>" +

                    "</div>";

            } else {
                if (rol_user == 'Administrador' || rol_user == 'Vendedor') {
                    return "<div id='" + full.id + "' class='text-center'>" +
                    "<div class='float-left col-lg-12'>" +
                    "<a href='" + urlActual + "/" + full.id + "' class='edit-factura' >" +
                    "<i class='fas fa-info-circle' title='Ver detalles de Factura'></i>" +
                    "</a>" + "</div>" + "</div>"
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
    if($('#ConfirmarAccionForm').valid()){
        confirmarAccion();
    }
});


function confirmarAccion(button) {
    $('.loader').fadeIn();
    var formData = $("#ConfirmarAccionForm").serialize();
    var id = $("#idConfirmacion").val();
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
        url: APP_URL + "/facturas/" + id + "/delete",
        data: formData,
        dataType: "json",
        success: function (data) {
            $('.loader').fadeOut(225);
            $('#modalConfirmarAccion').modal("hide");
            facturas_table.ajax.reload();
            alertify.set('notifier', 'position', 'top-center');
            alertify.success('¡La factura se anuló Correctamente!');
              window.location.reload();
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
Object.byString = function(o, s) {
    s = s.replace(/\[(\w+)\]/g, '.$1'); // convert indexes to properties
    s = s.replace(/^\./, ''); // strip a leading dot
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
data.forEach(function(row) {
    var dataRow = [];
    var i = 0;

    columns.forEach(function(column) {
        dataRow.push({
            text: Object.byString(row, column),
            alignment: headers[i].alignmentChild
        });
        i++;
    })
    body.push(dataRow);

});

return body;
}

// returns a pdfmake table
function tableF(data, columns, witdhsDef, showHeaders, headers, layoutDef) {
return {
    table: {
        headerRows: 1,
        widths: witdhsDef,
        body: buildTableBody(data, columns, showHeaders, headers)
    },
    layout: {
        fillColor: function(rowIndex, node, columnIndex) {
            return (rowIndex % 2 !== 0) ? ' #fff' : null;
        },
        hLineWidth: function(i, node) {
            return (i === 0 || i === node.table.body.length) ? 0 : 1;
        },
        vLineWidth: function(i, node) {
            return 0;
        },
        hLineColor: function(i, node) {
            return 'white';
        },
    }
};
}


//generate PDF report
$(document).on('click', 'a.imprimir-factura', function(event){
    var id = $(this).attr('id');
    var url = 'http://' + window.location.host + "/pedidos/getFactura/" + id;
    //get receipt header data and sets them to hidden inputs
    $.get(url, function(data) {
        $('#fac_id').val(data[0].id);
        $('#fac_fecha').val(data[0].fecha_factura);
        $('#fac_serie').val(data[0].serie_factura);
        $('#fac_no').val(data[0].no_factura);
        $('#fac_sub').val(data[0].subtotal);
        $('#fac_tax').val(data[0].impuestos);
        $('#fac_total').val(data[0].total);
        $('#fac_nombre').val(data[0].nombre_cliente);
        $('#fac_cliente_id').val(data[0].cliente_id);
        $('#fac_dias').val(data[0].dias_credito);
        $('#fac_nit').val(data[0].nit);
        $('#fac_address').val(data[0].direccion);
        $('#fac_tel').val(data[0].telefono_compras);
        $('#fac_seller').val(data[0].vendedor);
    });

    $.ajax({
        type: "GET",
        headers: {
            'X-CSRF-TOKEN': $('#tokenReset').val()
        },
        url:  "/pedidos/"+ id +"/getDetalles1",
        dataType: "json",
        success: function(data) {
            var details = data['data'];
            details = JSON.stringify(details);
            details = JSON.parse(details);
            for (let i = 0; i < details.length; i++) {
                details[i].precio   = "Q." + parseFloat(details[i].precio).toFixed(2);
                details[i].subtotal = "Q." + parseFloat(details[i].subtotal).toFixed(2);
            }

            let encabezadoFactura = {
                id:         $('#fac_id').val(),
                fecha:      $('#fac_fecha').val(),
                serie:      $('#fac_serie').val(),
                no:         $('#fac_no').val(),
                subtotal:   $('#fac_sub').val(),
                impuesto:   $('#fac_tax').val(),
                total:      $('#fac_total').val(),
                nombre:     $('#fac_nombre').val(),
                cliente_id: $('#fac_cliente_id').val(),
                credito:    $('#fac_dias').val(),
                nit:        $('#fac_nit').val(),
                direccion:  $('#fac_address').val(),
                telefono:   $('#fac_tel').val(),
                vendedor:   $('#fac_seller').val(),
            }
            var dd = {
                pageSize: {width: 8.5*72, height: 5.5*72},
                pageOrientation: 'landscape',
                pageMargins: [40, 40, 40, 50],
                defaultStyle : {
                    fillColor : "#fff"
                  },
                content: [
                    {text: "\n", lineHeight: 1.25},
                    {text: "\n", lineHeight: 1.25},
                    {text: "\n", lineHeight: 1.25},
                    {
                        columns: [
                            {
                                width: '5%',
                                text: ' '

                            },
                            {
                                text: encabezadoFactura.fecha,
                                style: 'data', alignment: 'left'
                            },
                            {
                                text: encabezadoFactura.nombre.split(/\s/).reduce((response,word)=> response+=word.slice(0,1),'').toUpperCase() + "-" + encabezadoFactura.cliente_id,
                                style:'data', alignment: 'right'
                            },
                            {
                                text: encabezadoFactura.credito + " días",
                                style: 'data', alignment: 'right'
                            },
                            {
                                text: encabezadoFactura.nit,
                                style: 'data', alignment: 'right'
                            },
                        ]
                    },
                    // { text: "\n" },
                    {
                        columns: [
                            {
                                width: '5%',
                                text: ' '
                            },
                            {
                                width: '70%',
                                text: encabezadoFactura.nombre,
                                style: 'data', alignment: 'left'
                            },
                            {
                                width: '25%',
                                text: encabezadoFactura.vendedor,
                                style: 'data', alignment: 'right'
                            },
                        ],
                    },
                    // { text: "\n" },
                    {
                        columns: [
                        {
                            width: '5%',
                            text: ' '
                        },
                        {
                            width: '70%',
                            text: encabezadoFactura.direccion,
                            style: 'data', alignment: 'left'
                        },
                        {
                            width: '25%',
                            text: encabezadoFactura.telefono, style: 'data', alignment: 'right'
                        },
                        ]
                    }, {
                        text: "\n", lineHeight: 0.8
                    },
                    tableF(
                        details,
                        ['codigo', 'cantidad', 'producto', 'precio', 'subtotal'],
                        ['10%', '10%', '60%', '10%', '10%'],
                        false,
                        [{
                                text: 'Código',
                                bold: 'true',
                                fontSizeChild: '7',
                            },
                            {
                                text: 'Cantidad',
                                bold: 'true',
                                fontSizeChild: '10',
                            },
                            {
                                text: 'Producto',
                                bold: 'true',
                                fontSizeChild: '10',
                                //alignmentChild: 'center',
                                //alignment: 'center'
                            },
                            {
                                text: 'Precio',
                                bold: 'true',
                                alignmentChild: 'right',
                                alignment: 'right',
                                fontSizeChild: '10',
                            },
                            {
                                text: 'Subtotal',
                                bold: 'true',
                                alignmentChild: 'right',
                                alignment: 'right',
                                fontSizeChild: '10',
                            }
                        ],
                        ''
                    ),
                ],
                 footer: {
                    columns:[
                        {
                            width: '10%',
                            text: ''
                        },
                        {
                            width: '60%',
                            text: 'Test text',
                            alignment: 'right',
                        },
                        {
                            width: '25%',
                            text: 'Test text',
                            alignment: 'right',
                        },
                        {
                            width: '5%',
                            text: ''
                        }
                    ],
                    columns:[
                        {
                            width: '10%',
                            text: ''
                        },
                        {
                            width: '60%',
                            text: numeroALetras(encabezadoFactura.total),
                            alignment: 'right',
                        },
                        {
                            width: '25%',
                            text: 'Q. ' + encabezadoFactura.total,
                            alignment: 'right',
                        },
                        {
                            width: '5%',
                            text: ''
                        }
                    ],
                  },
                styles: {
                    titulo: {
                        fontSize: 16,
                        bold: true
                    },
                    about: {
                        fontSize: 11,
                    },
                    subtitulo: {
                        fontSize: 11,
                        bold: true
                    },
                    data: {
                        fontSize: 11,
                        lineHeight: 1.5
                    },
                    fine: {
                        fontSize: 8,
                        bold: true,
                    }
                }
            }

            pdfMake.createPdf(dd).print();

        },
        error: function() {

        }
    });

})
