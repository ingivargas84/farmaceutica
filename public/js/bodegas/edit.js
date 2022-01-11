var validator = $("#EditarForm").validate({
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

$("#btnActualizar").click(function (event) {
    if ($('#EditarForm').valid()) {
      var btnAceptar=document.getElementById("btnActualizar");
      var disableButton = function() { this.disabled = true; };
      btnAceptar.addEventListener('click', disableButton , false);
        $('.loader').addClass("is-active");

    } else {
        validator.focusInvalid();
    }
});
