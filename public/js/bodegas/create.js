var validator = $("#InsertarForm").validate({
    ignore: [],
    onkeyup: false,
    rules: {
        nombre: {
            required: true,
        },
        descripcion: {
            required: true
        },
        tipo: {
            required: true
        }
    },
    messages: {
        nombre: {
            required: 'Por favor, ingrese el nombre',
        },
        descripcion: {
            required: 'Por favor, ingrese la descripción'
        },
        tipo: {
            required: 'Por favor, seleccione un tipo',
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
