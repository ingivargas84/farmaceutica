var validator = $("#ResetPasswordTerceroForm").validate({
	ignore: [],
	onkeyup:false,
    onclick: false,
    //onfocusout: false,
	rules: {
		password: {
			required: true
        },
        password_confirmation: {
            required: true
        }

	},
	messages: {
		password: {
			required: "Por favor, ingrese contraseña"
        },
        password_confirmation: {
            required: "Por favor, confirme contraseña"
        }
	}
});

$('#modalResetPasswordTercero').on('shown.bs.modal', function(event){
	var button = $(event.relatedTarget);
    var id = button.data('id');
    	
    var modal = $(this);
    modal.find(".modal-body input[name='id']").val(id);

    var nueva = $("input[name='id']").val();
    console.log(nueva);
 });
 
function BorrarFormularioReset() {
    $("#ResetPasswordTerceroForm :input").each(function () {
        $(this).val('');
	});
};

$("#btnResetPasswordTercero").click(function(event) {
	event.preventDefault();
	if ($('#ResetPasswordTerceroForm').valid()) {
		resetModalT();
	} else {
		validator.focusInvalid();
	}
});



if(window.location.hash === '#resetT')
{
	$('#modalResetPasswordTercero').modal('show');
}

$('#modalResetPasswordTercero').on('hide.bs.modal', function(){
	$("#ResetPasswordTerceroForm").validate().resetForm();
	document.getElementById("ResetPasswordTerceroForm").reset();
	window.location.hash = '#';

});

	$('#modalResetPasswordTercero').on('shown.bs.modal', function(){
	window.location.hash = '#resetT';

}); 




