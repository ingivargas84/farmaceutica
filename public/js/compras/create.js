$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");



$.validator.addMethod("fecha", function (value, element) {
    var valor = value;
    var fecha = new Date(valor);

    var anio = fecha.getFullYear().toString();
    var check = anio.length;

    if (check == 4) {
        return true;
    } else {
        return false;
    }
}, "El año no puede tener más de 4 dígitos.");



var validator = $('#CompraForm').validate({
    ignore: [],
    onkeyup: false,
    rules: {
        proveedor_id: {
            required: true,
            select: 'default'
        },
        serie_factura: {
            required: true,
        },
        bodega_id: {
            required: true,
            select: 'default'
        },
        fecha_compra: {
            required: true,
            fecha: true,
        },
        num_factura: {
            required: true,
            number: true,
        },
        fecha_factura: {
            required: true,
            fecha: true,
        },
        total_ingreso: {
            required: true,
        },
        caducidad:{
            // fecha: true,
        },
    },
    messages: {
        proveedor_id: {
            required: "Debe seleccionar un proveedor."
        },
        serie_factura: {
            required: "Este campo es obligatorio."
        },
        bodega_id: {
            required: "Este campo es obligatorio."
        },
        fecha_compra: {
            required: "Este campo es obligatorio."
        },
        num_factura: {
            required: "Este campo es obligatorio.",
            number: "Este campo solo acepta valores numéricos."
        },
        fecha_factura: {
            required: "Este campo es obligatorio."
        },
        total_ingreso: {
            required: "No puede registrar una compra sin productos"
        },
    }
});

