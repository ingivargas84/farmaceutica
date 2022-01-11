var validator = $("#UserForm").validate({
	ignore: [],
	onkeyup:false,
    onclick: false,
    //onfocusout: false,
	rules: {
		name:{
			required: true
		},

		username: {
			required : true
		},

		email: {
			required : true
		},

		roles: {
			required : true
		},
		password: {
			required: true
        },
        password_confirmation: {
            required: true
        }

	},
	messages: {
		name: {
			required: "Por favor, ingrese nombre"
		},

		username: {
			required: "Por favor, ingrese usuario"
		},

		email: {
			required: "Por favor, ingrese correo electronico"
		},

		roles: {
			required: "Por favor, seleccione rol"
		},
		password: {
			required: "Por favor, ingrese contraseña"
        },
        password_confirmation: {
            required: "Por favor, confirme contraseña"
        }
	}
});
function BorrarFormulario() {
    $("#UserForm :input").each(function () {
        $(this).val('');
	});
	$('#roles').val('');
	$('#roles').change();
};

$("#ButtonUserModal").click(function(event) {
	event.preventDefault();
	if ($('#UserForm').valid()) {
		saveModal();
	} else {
		validator.focusInvalid();
	}
});

function saveModal(button) {	
	var formData = $("#UserForm").serialize();
	var urlActual =  $("input[name='urlActual']").val();
	$.ajax({
		type: "POST",
		headers: {'X-CSRF-TOKEN': $('#tokenUser').val()},
		url: urlActual,
		data: formData,
		dataType: "json",
		success: function(data) {
			BorrarFormulario();
			$('#modalUser').modal("hide");
			users_table.ajax.reload();
			alertify.set('notifier','position', 'top-center');
			alertify.success('Usuario Creado con Éxito!!');
			
		},
		error: function(errors) {
			var errors = JSON.parse(errors.responseText);
			if (errors.email != null) {
				$("#UserForm input[name='email'] ").after("<label class='error' id='ErrorEmail'>"+errors.email+"</label>");
			}
			else{
				$("#ErrorEmail").remove();
			}

			if (errors.username != null) {
				$("#UserForm input[name='username'] ").after("<label class='error' id='ErrorUsername'>"+errors.username+"</label>");
			}
			else{
				$("#ErrorUsername").remove();
			}

			if (errors.password != null) {
				for (i = 0; i < errors.password.length; i++) {
					$("#UserForm input[name='password'] ").after("<label class='error' id='ErrorPassword'>"+errors.password[i]+"</br></label>");
				  }
			}
			else{
				$("#ErrorPassword").remove();
			}
		}
		
	});
}

if(window.location.hash === '#create')
	{
		$('#modalUser').modal('show');
	}

	$('#modalUser').on('hide.bs.modal', function(){
		$("#UserForm").validate().resetForm();
		document.getElementById("UserForm").reset();
		window.location.hash = '#';
	});

	$('#modalUser').on('shown.bs.modal', function(){
		window.location.hash = '#create';

	}); 