$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");
$.validator.addMethod('entero', function(value, element){
    var regex = new RegExp("^(0+[1-9]|[1-9])[0-9]*$");
    return regex.test(value);
},"Esta cantidad no puede ser menor o igual a 0");


var validator = $('#DevolucionForm').validate({
    ignore: [],
    onkeyup: false,
    rules: {
        bodega: {
            required: true,
            select: 'default',
        },
    },
    messages: {
        bodega: {
            required: "Debe seleccionar un pedido."
        },
    }
});
