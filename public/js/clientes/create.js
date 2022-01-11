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

$.validator.addMethod("nit", function (value, element) {
    var valor = value;

    if (nitIsValid(valor) == true) {
        return true;
    }

    else {
        return false;
    }
}, "El NIT ingresado es incorrecto o inválido, reviselo y vuelva a ingresarlo");

$.validator.addMethod("fecha", function(value, element){
    var valor = value;
    var fecha = new Date(valor);

    var anio = fecha.getFullYear().toString();
    var check = anio.length;

    if (check == 4) {
        return true;
    } else {
        return false;
    }
}, "El año de nacimiento no puede ser mayor a 4 dígitos.");

$.validator.addMethod("ntel", function (value, element) {
    var valor = value.length;
    if (valor == 8) {
        return true;
    }
    else {
        return false;
    }
}, "Debe ingresar el número de teléfono con 8 dígitos, en formato ########");

$.validator.addMethod('entero', function (value, element) {
    var regex = new RegExp("^(0+[0-9]|[0-9])[0-9]*$");
    return regex.test(value);
}, "Esta cantidad no puede ser menor  a 0");

var validator = $("#ClienteForm").validate({
    ignore: [],
    onkeyup: false,
    rules: {
        nombre_cliente: {
            required: true,
        },
        dias_credito: {
            required: true,
            number: true,
            entero: true
        },
        direccion: {
            required: true
        },
        territorio: {
            required: true
        },
    },
    messages: {
        nombre_cliente: {
            required: 'Por favor, ingrese el nombre del cliente',
        },
        dias_credito: {
            required: 'Por favor, ingrese la cantidad de días',
            number: 'Este campo solo admite valores numericos'
        },
        direccion: {
            required: 'Por favor, ingrese la dirección'
        },
        territorio: {
            required: 'Por favor, seleccione un territorio'
        },

    }
});

$("#ButtonCliente").click(function (event) {
    if ($('#ClienteForm').valid()) {
        $('.loader').addClass("is-active");
        var btnAceptar=document.getElementById("ButtonCliente");
        var disableButton = function() { this.disabled = true; };
        btnAceptar.addEventListener('click', disableButton , false);
    } else {
        validator.focusInvalid();
    }
});
