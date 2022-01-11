var validator = $("#VisitasForm").validate({
	ignore: [],
	onkeyup:false,
	rules: {
		cliente_id:{
			required: true
		},
		observaciones:{
			required: true
		}

	},
	messages: {
		cliente_id: {
			required: "Por favor, seleccione un cliente"
		},
		observaciones: {
			required: "Por favor, ingrese las observaciones correspondientes"
		}

	}
});


var validator = $("#Visitas2Form").validate({
	ignore: [],
	onkeyup:false,
	rules: {
		nombre_cliente:{
			required: true
		},
		direccion:{
			required: true
		},
		telefono:{
			required: true
		},
		observaciones:{
			required: true
		}

	},
	messages: {
		nombre_cliente: {
			required: "Por favor, escriba el nombre del cliente"
		},
		direccion: {
			required: "Por favor, escriba la dirección del cliente"
		},
		telefono: {
			required: "Por favor, escriba el teléfono del cliente"
		},
		observaciones: {
			required: "Por favor, ingrese las observaciones correspondientes"
		}

	}
});

$("#ButtonVisita").click(function(event) {
	if ($('#VisitasForm').valid()) {
		$('.loader').addClass("is-active");
		var btnAceptar=document.getElementById("ButtonVisita");
		var disableButton = function() { this.disabled = true; };
		btnAceptar.addEventListener('click', disableButton , false);
	} else {
		validator.focusInvalid();
	}
});

$("#ButtonVisita2").click(function(event) {
	if ($('#Visitas2Form').valid()) {
		$('.loader').addClass("is-active");
		var btnAceptar=document.getElementById("ButtonVisita2");
		var disableButton = function() { this.disabled = true; };
		btnAceptar.addEventListener('click', disableButton , false);
	} else {
		validator.focusInvalid();
	}
});
