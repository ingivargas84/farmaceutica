$.validator.addMethod("select", function (value, element, arg) {
    return arg !== value;
}, "Debe seleccionar una opción.");
$.validator.addMethod('entero', function(value, element){
    var regex = new RegExp("^(1+[1-9]|[1-9])[1-9]*$");
    return regex.test(value);
},"Esta cantidad no puede ser menor a 0");

$.validator.addMethod('mayor', function(value, element){
  if ( parseFloat($('#cantidad').val()) <= parseFloat($('#cantidad1').val()) ) {
      return true
  }else{
      return false
  }
},"Esta cantidad no puede ser mayor a la anterior");


var validator = $('#EditarForm').validate({
    ignore: [],
    onkeyup: false,
    rules: {
        cantidad: {
            select: 'default',
            entero: true,
            mayor: true,
        },
    },
    messages: {
        cantidad: {
            required: "Debe ingresar una cantidad."
        },
    }
});
