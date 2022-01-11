var visitas_table = $('#visitas-table').DataTable({
    "responsive": true,
    "processing": true,
    "info": true,
    "showNEntries": true,
    "dom": 'Bfrtip',

    lengthMenu: [
        [ 10, 25, 50, -1 ],
        [ '10 filas', '25 filas', '50 filas', 'Mostrar todo' ]
    ],

    "buttons": [
    'pageLength',
    'excelHtml5',
    'csvHtml5'
    ],

    "paging": true,
    "language": {
        "sdecimal":        ".",
        "sthousands":      ",",
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
    },
    "order": [0, 'desc'],

    "columns": [ {
        "title": "Fecha",
        "data": "created_at",
        "width" : "10%",
        "responsivePriority": 4,
        "render": function( data, type, full, meta ) {
            return (data);},
    },
       
    {
        "title": "Cliente",
        "data": "nombre_cliente",
        "width" : "10%",
        "responsivePriority": 1,
        "render": function( data, type, full, meta ) {
            return (data);},
    }, 

    {
        "title": "Dirección",
        "data": "direccion",
        "width" : "20%",
        "responsivePriority": 4,
        "render": function( data, type, full, meta ) {
            return (data);},
    },

    {
        "title": "Observaciones",
        "data": "observaciones",
        "width" : "50%",
        "responsivePriority": 4,
        "render": function( data, type, full, meta ) {
            return (data);},
    },     
     
    {
        "title": "Acciones",
        "orderable": false,
        "width" : "10%",
        "render": function(data, type, full, meta) {
            var rol_user = $("input[name='rol_user']").val();
            var urlActual = $("input[name='urlActual']").val();

                return "<div id='" + full.id + "' class='text-center'>" + 
                "<div class='float-left col-lg-6'>" + 
                "<a href='" + urlActual + "/edit/" + full.id + "' class='edit-visita' >" +
                "<i class='fa fa-btn fa-edit' title='Editar Visita'></i>" +
                "</a>" + "</div>" + 
                "<div class='float-right col-lg-6'>" + 
                "<a href='#' class='remove-visita' data-method='delete' data-id='"+full.id+"' data-target='#modalConfirmarAccion' data-toggle='modal'>" + 
                "<i class='fas fa-trash-alt' title='Eliminar Visita'></i>" + 
                "</a>" + "</div>";     
        },
        "responsivePriority": 5
    }]

});

//Confirmar Contraseña para borrar
$("#btnConfirmarAccion").click(function(event) {
    event.preventDefault();
	if ($('#ConfirmarAccionForm').valid()) {
		confirmarAccion();
	} else {
		validator.focusInvalid();
	}
});


function confirmarAccion(button) {
    $('.loader').fadeIn();	
    var formData = $("#ConfirmarAccionForm").serialize();
    var id = $("#idConfirmacion").val();
	$.ajax({
		type: "POST",
		headers: {'X-CSRF-TOKEN': $('#tokenReset').val()},
		url: APP_URL+"/visitas/" + id + "/delete",
		data: formData,
		dataType: "json",
		success: function(data) {
            $('.loader').fadeOut(225);
			$('#modalConfirmarAccion').modal("hide");
			territorios_table.ajax.reload();      
			alertify.set('notifier','position', 'top-center');
			alertify.success('La Visita se Eliminó Correctamente!!');
		},
		error: function(errors) {
            $('.loader').fadeOut(225);
            if(errors.responseText !=""){
                var errors = JSON.parse(errors.responseText);
                if (errors.password_actual != null) {
                    $("input[name='password_actual'] ").after("<label class='error' id='ErrorPassword_actual'>"+errors.password_actual+"</label>");
                }
                else{
                    $("#ErrorPassword_actual").remove();
                }
            }
            
		}
		
	});
}



