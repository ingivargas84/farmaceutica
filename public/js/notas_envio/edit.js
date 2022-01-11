var validator = $("#EditarForm").validate({
    ignore: [],
    onkeyup: false,
    rules: {
        nombre: {
            required: true,
        },
        direccion: {
            required: true
        },
        telefono: {
            required: true,
            number: true,
            maxlength: 8,
        }
    },
    messages: {
        nombre: {
            required: 'Por favor, ingrese el nombre',
        },
        direccion: {
            required: 'Por favor, ingrese la direccion'
        },
        telefono: {
            required: 'Por favor, ingrese el teléfono.',
            number: 'Este campo solo acepta valores numéricos',
            maxlength: 'Debe ingresar el número de teléfono con 8 dígitos, en formato ########'
        }
    }
});

$("#btnActualizar").click(function (event) {
    if ($('#EditarForm').valid()) {
        $('.loader').addClass("is-active");
    } else {
        validator.focusInvalid();
    }
});