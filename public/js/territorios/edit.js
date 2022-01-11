var validator = $("#EditarForm").validate({
    ignore: [],
    onkeyup: false,
    rules: {
        territorio: {
            required: true,
            territorioUnicoEditar: true,
        },
        descripcion: {
            required: true
        },
    },
    messages: {
        territorio: {
            required: 'Por favor, ingrese el nombre',
        },
        descripcion: {
            required: 'Por favor, ingrese la descripción'
        },
    }
});

$("#btnActualizar").click(function (event) {
    if ($('#EditarForm').valid()) {
        $('.loader').addClass("is-active");
        var btnAceptar=document.getElementById("btnActualizar");
        var disableButton = function() { this.disabled = true; };
        btnAceptar.addEventListener('click', disableButton , false);
    } else {
        validator.focusInvalid();
    }
});
