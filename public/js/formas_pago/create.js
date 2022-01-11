var validator = $("#InsertarForm").validate({
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

$("#btnInsertar").click(function (event) {
    if ($('#InsertarForm').valid()) {
        $('.loader').addClass("is-active");
        var btnAceptar=document.getElementById("btnInsertar");
        var disableButton = function() { this.disabled = true; };
        btnAceptar.addEventListener('click', disableButton , false);
    } else {
        validator.focusInvalid();
    }
});
