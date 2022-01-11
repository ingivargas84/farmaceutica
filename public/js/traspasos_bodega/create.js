$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");

$.validator.addMethod("diff", function(value, element, arg){
    if ($('#b-1').val() == $('#b-2').val()) {
        return false
    }else{
        return true
    }
},"La bodega de destino no puede ser la misma de origen");

$.validator.addMethod('entero', function (value, element) {
    var regex = new RegExp("^(0+[1-9]|[1-9])[0-9]*$");
    return regex.test(value);
}, "Esta cantidad no puede ser menor o igual a 0");

$.validator.addMethod('existencia', function(value, element){
    var max = parseInt($('#existencias').val());
    if (parseInt(value) <= max) {
        return true
    }else{
        return false
    }
}, "No puede traspasar más productos de los que se encuentran en existencias");

var validator = $("#TraspasoForm").validate({
    ignore: [],
    onkeyup: false,
    rules: {
        bodega_origen: {
            select: 'default',
        },
        bodega_destino: {
            select: 'default',
            diff: true,
        },
        cantidad: {
            required: true,
            entero: true,
            existencia: true,
        },
    },
    messages: {
        cantidad: {
            required: 'Por favor, ingrese la cantidad',
        },
    }
});

$("#ButtonTraspaso").click(function (event) {
    if ($('#ProductoForm').valid()) {
        $('.loader').addClass("is-active");
        var btnAceptar=document.getElementById("ButtonTraspaso");
        var disableButton = function() { this.disabled = true; };
        btnAceptar.addEventListener('click', disableButton , false);
    } else {
        validator.focusInvalid();
    }
});
