$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");
var validator = $("#reporteMensual").validate({

	ignore: [],
	onkeyup:false,
	rules: {
		mes:{
		  select: 'default',

		},
	 anio: {
			  select: 'default',
		}
	},
	messages: {
		mes: {
			required: "Por favor, seleccionar mes"
		},
		anio: {
			required: "Por favor, seleccionar a�0�9o"
		},
	}
});
