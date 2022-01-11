$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");

$.validator.addMethod("ntel", function (value, element) {
    var valor = value.length;
    if (valor == 8) {
        return true;
    }
    else {
        return false;
    }
}, "Debe ingresar el número de teléfono con 8 dígitos, en formato ########");

var validator = $('#NotaEnvioForm').validate({
    ignore: [],
    onkeyup: false,
    rules: {
        pedidos: {
            required: true,
            select: 'default'
        },
        telefono: {
            required: true,
            ntel: true,
            number: true,
        },
        direccion: {
            required: true,
        },
        cliente: {
            required: true,
        }
    },
    messages: {
        telefono: {
            required: 'Este campo es requerido.',
            number: 'Este campo solo acepta caracteres numéricos.'
        },
        direccion: {
            required: 'Este campo es requerido.'
        },
        cliente: {
            required: 'Este campo es requerido.'
        }
    }
});

