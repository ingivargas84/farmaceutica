var validator = $("#UserUpdateForm").validate({
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
		}
	}
});

$('#modalUpdateUser').on('shown.bs.modal', function(event){
	var button = $(event.relatedTarget);
	var id = button.data('id');
	var name = button.data('name_user');
	var username = button.data('username');
	var email = button.data('email');
	var rol = button.data('rol');
	
	var modal = $(this);
	modal.find(".modal-body input[name='id']").val(id);
	modal.find(".modal-body input[name='email']").val(email);
	modal.find(".modal-body input[name='username']").val(username);
	modal.find(".modal-body input[name='name']").val(name);
	modal.find(".modal-body #roles2").val(rol);

 }); 

function BorrarFormularioUpdate() {
    $("#UserUpdateForm :input").each(function () {
        $(this).val('');
	});
	$('#roles2').val('');
	$('#roles2').change();
};

$("#ButtonUserModalUpdate").click(function(event) {
	event.preventDefault();
	if ($('#UserUpdateForm').valid()) {
		updateModal();
	} else {
		validator.focusInvalid();
	}
});

function updateModal(button) {
	var formData = $("#UserUpdateForm").serialize();
	var id = $("input[name='id']").val();
	var urlActual =  $("input[name='urlActual']").val();
	$.ajax({
		type: "post",
		headers: {'X-CSRF-TOKEN': $('#tokenUserEdit').val()},
		url: urlActual+"/update/"+id,
		data: formData,
		dataType: "json",
		success: function(data) {
			BorrarFormularioUpdate();
			$('#modalUpdateUser').modal("hide");
			users_table.ajax.reload();
			alertify.set('notifier','position', 'top-center');
			alertify.success('Usuario Editado con Éxito!!');
		},
		error: function(errors) {
			var errors = JSON.parse(errors.responseText);
			if (errors.email != null) {
				$("#UserUpdateForm input[name='email'] ").after("<label class='error' id='ErrorEmailedit'>"+errors.email+"</label>");
			}
			else{
				$("#ErrorEmailedit").remove();
			}

			if (errors.username != null) {
				$("#UserUpdateForm input[name='username'] ").after("<label class='error' id='ErrorUsernameedit'>"+errors.username+"</label>");
			}
			else{
				$("#ErrorUsernameedit").remove();
			}
		}
		
	});
}

if(window.location.hash === '#edit')
		{
			$('#modalUpdateUser').modal('show');
		}

		$('#modalUpdateUser').on('hide.bs.modal', function(){
			$("#UserUpdateForm").validate().resetForm();
			document.getElementById("UserUpdateForm").reset();
			window.location.hash = '#';
		});

		$('#modalUpdateUser').on('shown.bs.modal', function(){
			window.location.hash = '#edit';

	}); 
	   

/*$(".edit-user").click(function(){
	alert($(".edit-user").attr("data-id"));
	console.log('si');

});*/




