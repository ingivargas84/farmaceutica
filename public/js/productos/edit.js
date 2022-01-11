$.validator.addMethod('entero', function (value, element) {
    var regex = new RegExp("^(0+[1-9]|[1-9])[0-9]*$");
    return regex.test(value);
}, "Esta cantidad no puede ser menor o igual a 0");

$.validator.addMethod('dinero', function (value, element) {
    var regex = new RegExp("^\\d+(?:\\.\\d{0,2})?$");
    return regex.test(value);
}, "El producto no puede costar menos de Q 0.00");

var validator = $("#ProductoForm").validate({
    ignore: [],
    onkeyup: false,
    rules: {
        codigo: {
            required: true,
        },
        nombre_comercial: {
            required: true
        },
        nombre_generico: {
            required: true
        },
        concentracion: {
            required: true
        },
        precio_venta: {
            required: true,
            number: true,
            dinero: true
        },
        presentacion: {
            required: true,
        },
        stock_maximo: {
            required: true,
            number: true,
            entero: true
        },
        stock_minimo: {
            required: true,
            number: true,
            entero: true
        },
    },
    messages: {
        codigo: {
            required: 'Por favor, ingrese el codigo',
        },
        nombre_comercial: {
            required: 'Por favor, ingrese el nombre comercial'
        },
        nombre_generico: {
            required: 'Por favor, ingrese el nombre genérico',
        },
        concentracion: {
            required: 'Por favor, ingrese la concentrción'
        },
        precio_venta: {
            required: 'Por favor, ingrese el precio de venta',
            number: 'Este campo solo admite valores numéricos'
        },
        presentacion: {
            required: 'Por favor, seleccione una presentación',
        },
        stock_maximo: {
            required: 'Por favor, ingrese el stock máximo',
            number: 'Este campo solo admite valores numéricos'
        },
        stock_minimo: {
            required: 'Por favor, ingrese el stock mínimo',
            number: 'Este campo solo admite valores numéricos'
        },
    }
});

$("#ButtonProductoUpdate").click(function (event) {
    if ($('#ProductoForm').valid()) {
        $('.loader').addClass("is-active");
        var btnAceptar=document.getElementById("ButtonProductoUpdate");
        var disableButton = function() { this.disabled = true; };
        btnAceptar.addEventListener('click', disableButton , false);
    } else {
        validator.focusInvalid();
    }
});
