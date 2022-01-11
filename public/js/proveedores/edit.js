//Funcion para validar NIT
function nitIsValid(nit) {
	if (!nit) {
		return true;
	}

	var nitRegExp = new RegExp('^[0-9]+(-?[0-9kK])?$');

	if (!nitRegExp.test(nit)) {
		return false;
	}

	nit = nit.replace(/-/, '');
	var lastChar = nit.length - 1;
	var number = nit.substring(0, lastChar);
	var expectedCheker = nit.substring(lastChar, lastChar + 1).toLowerCase();

	var factor = number.length + 1;
	var total = 0;

	for (var i = 0; i < number.length; i++) {
		var character = number.substring(i, i + 1);
		var digit = parseInt(character, 10);

		total += (digit * factor);
		factor = factor - 1;
	}

	var modulus = (11 - (total % 11)) % 11;
	var computedChecker = (modulus == 10 ? "k" : modulus.toString());

	return expectedCheker === computedChecker;
}

$.validator.addMethod("nit", function(value, element){
var valor = value;

if(nitIsValid(valor)==true)
{
	return true;
}

else
{
	return false;
}
}, "El NIT ingresado es incorrecto o inválido, reviselo y vuelva a ingresarlo");


$.validator.addMethod("ntel", function(value, element) {
	var valor = value.length;
	if (valor == 8)
	{
		return true;
	}
	else
	{
		return false;
	}
}, "Debe ingresar el número de teléfono con 8 dígitos, en formato ########");

$.validator.addMethod('entero', function (value, element) {
    var regex = new RegExp("^(0+[0-9]|[0-9])[0-9]*$");
    return regex.test(value);
}, "Esta cantidad no puede ser menor  a 0");

var validator = $("#ProveedorUpdateForm").validate({
	ignore: [],
	onkeyup:false,
	rules: {
		nit:{
			required: true,
			nit:true
		},
		nombre_comercial: {
			required : true
		},
		telefono: {
			ntel:true,
			required: true
		},

		direccion: {
			required: true
		},
		contacto1:{
			required: true
		},
		dias_credito:{
			required: true,
			number: true,
			entero: true
		}
	},
	messages: {
		nit: {
			required: "Por favor, ingrese el nit"
		},
		nombre_comercial: {
			required: "Por favor, ingrese el nombre comercial"
		},

		telefono: {
			required: "Por favor, ingrese el número de teléfono"
		},
		direccion: {
			required: "Por favor, ingrese la dirección"
		},
		contacto1: {
			required: "Por favor, ingrese datos de contacto 1"
		},
		dias_credito: {
			required: "Por favor, ingrese los días de crédito",
			number: 'Este campo solo admite valores numericos'
		}
	}
});

$("#ButtonProveedorUpdate").click(function(event) {
	if ($('#ProveedorUpdateForm').valid()) {
		$('.loader').addClass("is-active");
		var btnAceptar=document.getElementById("ButtonProveedorUpdate");
		var disableButton = function() { this.disabled = true; };
		btnAceptar.addEventListener('click', disableButton , false);
	} else {
		validator.focusInvalid();
	}
});
