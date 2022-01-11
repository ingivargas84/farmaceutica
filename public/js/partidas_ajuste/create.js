$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");

var validator = $('#PartidaForm').validate({
    onkeyup: false,
    ignore: [],
    rules: {
        saldo: {
            required: true,
        },
        total_ingreso: {
            required: true,
        },
        total_salida: {
            required: true,
        },
    },
    messages: {
        saldo: {
            required: "El saldo no puede ser nulo."
        },
        total_ingreso: {
            required: "El total de ingresos no puede ser nulo."
        },
        total_salida: {
            required: "El total de salidas no puede ser nulo."
        },
    }
});

