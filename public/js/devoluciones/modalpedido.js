$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");

var validator = $('#devoluciones').validate({
    ignore: [],
    onkeyup: false,
    rules: {
        pedidoDevolucion: {
            required: true,
            select: 'default'
        },
    },
    messages: {
        pedidoDevolucion: {
            required: "Debe seleccionar un pedido."
        },
    }
});
