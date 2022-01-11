var users_table = $('#users-table').DataTable({
    //"ajax": "/user/getJson",
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
    'csvHtml5',
    'pdfHtml5'
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
        "visible": false,
        "title": "No.",
        "data": "id",
        "width" : "0%",
        "responsivePriority": 1,
        "render": function( data, type, full, meta ) {
            return (data);},
    },
    
    {
        "title": "Username",
        "data": "username",
        "width" : "10%",
        "responsivePriority": 1,
        "render": function( data, type, full, meta ) {
            return (data);},
    }, 
    
    {
        "title": "Nombre",
        "data": "name",
        "width" : "20%",
        "responsivePriority": 1,
        "render": function( data, type, full, meta ) {
            return (data);},
    }, 

    {
        "title": "Email",
        "data": "email",
        "width" : "15%",
        "responsivePriority": 2,
        "render": function( data, type, full, meta ) {
            return (data);},
    }, 

    {
        "title": "Rol",
        "data": "rol",
        "width" : "15%",
        "responsivePriority": 2,
        "render": function( data, type, full, meta ) {
            return (data);},
    }, 

    {
        "title": "Estado",
        "data": "estado",
        "width" : "10%",
        "responsivePriority": 3,
        "render": function( data, type, full, meta ) {
            return (data);},
    },
    
          
    {
        "title": "Acciones",
        "data": "estado",
        "orderable": false,
        "width" : "20%",
        "render": function(data, type, full, meta) {
            var rol_user = $("input[name='rol_user']").val();
            var urlActual =  $("input[name='urlActual']").val();
            if(rol_user == 'Super-Administrador' && data == 'Activo' || rol_user == 'Administrador' && data == 'Activo'){

                return "<div id='" + full.id + "' class='text-center'>" + 
                "<div class='float-left col-lg-4'>" + 
                "<a href='#' class='edit-user' data-toggle='modal' data-target='#modalUpdateUser' data-id='"+full.id+"' data-email='"+full.email+"' data-username='"+full.username+"' data-rol='"+full.rol+"' data-name_user='"+full.name+"'>" + 
                "<i class='fa fa-btn fa-edit' title='Editar Usuario'></i>" + 
                "</a>" + "</div>" + 
                "<div class='float-right col-lg-4'>" + 
                "<a href='"+urlActual+"/"+full.id+"' class='remove-user'"+ "data-method='delete'"+ ">" + 
                "<i class='fa fa-thumbs-down' title='Desactivar Usuario'></i>" + 
                "</a>" + "</div>"+
                "<div class='float-left col-lg-4'>" + 
                "<a href='#' class='reset-password'>" + 
                "<i class='fa fa-btn fa-lock-open' title='Resetear Password' data-toggle='modal' data-target='#modalResetPasswordTercero' data-id='"+full.id+"'></i>" + 
                "</a>" + "</div>" ;                
            }
            else if(rol_user == 'Super-Administrador' && data == 'Inactivo' || rol_user == 'Administrador' && data == 'Inactivo'){
                return "<div id='" + full.id + "' class='text-center'>" + 
                "<div class='float-left col-lg-6'>" + 
                "<a href='#' class='edit-user' data-toggle='modal' data-target='#modalUpdateUser' data-id='"+full.id+"' data-email='"+full.email+"' data-username='"+full.username+"' data-rol='"+full.rol+"' data-name_user='"+full.name+"'>" + 
                "<i class='fa fa-btn fa-edit' title='Editar Usuario'></i>" + 
                "</a>" + "</div>" +
                "<div class='float-left col-lg-6'>" + 
                "<a href='#' class='reset-password'>" + 
                "<i class='fa fa-btn fa-lock-open' title='Resetear Password' data-toggle='modal' data-target='#modalResetPasswordTercero' data-id='"+full.id+"'></i>" + 
                "</a>" + "</div>" ; 
            }
            else{
                return "<div id='" + full.id + "' class='text-center'>" + 
                "<div class='float-left col-lg-12'>" + 
                "<a href='#' class='edit-user' data-toggle='modal' data-target='#modalUpdateUser' data-id='"+full.id+"' data-email='"+full.email+"' data-username='"+full.username+"' data-rol='"+full.rol+"' data-name_user='"+full.name+"'>" + 
                "<i class='fa fa-btn fa-edit' title='Editar Usuario'></i>" + 
                "</a>" + "</div>";

            }
            
            
        },
        "responsivePriority": 4
    }]

});


$(document).on('click', 'a.remove-user', function(e) {
    e.preventDefault(); // does not go through with the link.

    var $this = $(this);    
    alertify.confirm('Desactivar Usuario', 'Esta seguro de desactivar el usuario', 
        function(){
            $.post({
                type: $this.data('method'),
                url: $this.attr('href')
            }).done(function (data) {
                users_table.ajax.reload();
                    alertify.set('notifier','position', 'top-center');
                    alertify.success('Usuario Desactivado con Éxito!!');
            }); 
         }
        , function(){
            alertify.set('notifier','position', 'top-center'); 
            alertify.error('Cancelar')
        });   
});

/*function confirmar() {
    var txt;
    if (confirm("Press a button!")) {
      txt = "You pressed OK!";
    } else {
      txt = "You pressed Cancel!";
    }
    document.getElementById("demo").innerHTML = txt;
  }*/