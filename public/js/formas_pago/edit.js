var validator = $("#EditarForm").validate({
    ignore: [],
    onkeyup: false,
    onfocusout: false,
    rules: {
        nombre: {
            required: true,
            nombreUnico: true,
        }
    },
    messages: {
        nombre: {
            required: 'Por favor, ingrese el nombre',
        }
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
