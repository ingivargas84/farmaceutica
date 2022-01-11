$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");

$.validator.addMethod('existencia', function (value, element) {
    var max = parseInt($('#stock').val());
    if (parseInt(value) <= max && parseInt(value) > 0) {
        return true
    } else {
        return false
    }
}, "No puede hacer un pedido de mis productos de los que hay en bodega,  de 0 productos, o menor");

var validator = $('#PedidoForm').validate({
    onkeyup: false,
    ignore: [],
    rules: {
        cliente_id: {
            required: true,
            select: 'default'
        },
        cantidad: {
            existencia: true,
        }
    },
    messages: {
        cliente_id: {
            required: "Debe seleccionar un cliente."
        },
    }
});
