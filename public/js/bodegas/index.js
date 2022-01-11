var bodegas_table = $('#bodegas-table').DataTable({
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
            title: 'Bodegas',
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
        "title": "Nombre",
        "data": "nombre",
        "width": "10%",
        "responsivePriority": 2,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Descripción",
        "data": "descripcion",
        "width": "10%",
        "responsivePriority": 1,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },

    {
        "title": "Tipo de Bodega",
        "data": "tipo",
        "width": "15%",
        "responsivePriority": 4,
        "render": function (data, type, full, meta) {
            return (data);
        },
    },
    {
        "title": "Encargado de Bodega",
        "data": "encargado",
        "width": "15%",
        "responsivePriority": 4,
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
                    "<a id='modal_edit' class='edit-bodega'>" +
                    "<i class='fa fa-btn fa-edit' title='Editar Bodega'></i>" +
                    "</a>" + "</div>" +
                    "<div class='float-right col-lg-4'>" +
                    "<a href='#' class='remove-bodega' data-method='delete' data-id='" + full.id + "' data-target='#modalConfirmarAccion' data-toggle='modal'>" +
                    "<i class='fa fa-thumbs-down' title='Desactivar Bodega'></i>" +
                    "</a>" + "</div>";

            } else {
                if (rol_user == 'Super-Administrador' || rol_user == 'Administrador') {
                    return "<div id='" + full.id + "' class='text-center'>" +
                        "<div class='float-right col-lg-6'>" +
                        "<a href='" + urlActual + "/" + full.id + "/activar' class='activar-bodega'" + "data-method='post' data-id='" + full.id + "' >" +
                        "<i class='fa fa-thumbs-up' title='Activar Bodega'></i>" +
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
        url: APP_URL + "/bodegas/" + id + "/delete",
        data: formData,
        dataType: "json",
        success: function (data) {
            $('.loader').fadeOut(225);
            $('#modalConfirmarAccion').modal("hide");
            bodegas_table.ajax.reload();
            alertify.set('notifier', 'position', 'top-center');
            alertify.success('La Bodega se Desactivó Correctamente!!');
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

//$(document).on('click', 'a.activar-bodega', function (e) {
  //  e.preventDefault(); // does not go through with the link.

  //  var $this = $(this);
    //alertify.confirm('Activar Bodega', 'Esta seguro de activar la bodega',
      //  function () {
        //    $('.loader').fadeIn();
          //  $.post({
            //    type: $this.data('method'),
              //  url: $this.attr('href')
          //  }).done(function (data) {
            //    $('.loader').fadeOut(225);
              //  bodegas_table.ajax.reload();
                //alertify.set('notifier', 'position', 'top-center');
                //alertify.success('Bodega Activada con Éxito!!');
            //});
        //}
        //, function () {
          //  alertify.set('notifier', 'position', 'top-center');
            //alertify.error('Cancelar')
        //});
//});

$(document).on('click', 'a.activar-bodega', function(){

    var url = $this.attr('href')
    $.ajax({
        url: url,
        success: function(data){
          $('.loader').fadeOut(225);
           bodegas_table.ajax.reload();
          alertify.set('notifier', 'position', 'top-center');
          alertify.donde  (
              'Bodega Activada con Éxito!!');
            },
          error: function(){
            alertify.set('notifier', 'position', 'top-center');
            alertify.error  (
                'El usuario, ya tiene asignado un bodega activa.');
              }
            });
          });
