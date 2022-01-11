$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");

var validator = $("#TerritorioForm").validate({
	ignore: [],
	onkeyup:false,
	rules: {
		territorio:{
			required: true,
			territorioUnico: true,
		},
		descripcion:{
			required: true
		},
	//	vendedor:{
		//	required: true,
		//	select: 'default'
		//}

	},
	messages: {
		territorio: {
			required: "Por favor, ingrese datos del territorio"
		},
		descripcion: {
			required: "Por favor, ingrese datos de una descripción"
		},
	//	vendedor: {
		//	required:"por favor, seleccione vendedor"
	//	}

	}
});

$("#ButtonTerritorio").click(function(event) {
	if ($('#TerritorioForm').valid()) {
		$('.loader').addClass("is-active");
		var btnAceptar=document.getElementById("ButtonTerritorio");
		var disableButton = function() { this.disabled = true; };
		btnAceptar.addEventListener('click', disableButton , false);
	} else {
		validator.focusInvalid();
	}
});
