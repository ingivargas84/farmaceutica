$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");

$.validator.addMethod('existencia1', function (value, element) {
    var max = parseInt($('#existencias').val());
    if (parseInt(value) <= max && parseInt(value) > 0) {
        return true
    } else {
        return false
    }
}, "No puede hacer un pedido de más productos de los que hay en bodega,  de 0 productos, o menor");

var validator = $('#EditarForm').validate({
    onkeyup: false,
    ignore: [],
    rules: {
      cantidaddetalle: {
            required: true,
            existencia1: true,
        }
    },
    messages: {
        cantidaddetalle: {
            required: "Debe ingresar la cantidad a editar"
        },
    }
});
