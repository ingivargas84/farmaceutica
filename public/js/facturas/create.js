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
    var regex = new RegExp("^(0+[1-9]|[1-9])[0-9]*$");
    return regex.test(value);
}, "Esta cantidad no puede ser menor o igual a 0");

$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");

var validator = $("#InsertarForm").validate({
    ignore: [],
    onkeyup: false,
    rules: {
        factura: {
            required: true,
            serieUnica:true
        },
        nit: {
            required: true,
        },
        direccion: {
            required: true
        },
        cliente: {
            required: true
        },
        serie: {
            required: true,

        },
        pedido: {
          required: true,
        },
        fecha_fac: {
            required: true,
        },
    },
    messages: {
        factura: {
            required: 'Por favor, Número Factura',
        },
        nit: {
            required: 'Por favor, ingrese el nit',
        },
        direccion: {
            required: 'Por favor, ingrese la dirección'
        },
        cliente: {
            required: 'Por favor, ingrese nombre del cliente'
        },
        serie: {
            required: 'Por favor, ingrse el numero de serie'
        },
        pedido: {
            required: 'Por favor, Seleccione pedido'
        },
        fecha_fac: {
            required: 'Por favor, Seleccione fecha'
        },

    }
});
