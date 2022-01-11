<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <p>Estimado cliente, le informamos que se ha creado un nuevo pedido que requiere facturación:
      <br>
      <b> No. nota de envio: {{$nota}}, cliente: {{$cliente}}, Total : Q.<?php echo number_format($total, 2, ".", ",");?>, Vendedor: {{$vendedor}}</b>
      <br>
Muchas gracias por utilizar nuestros servicios en línea. </p>
  </body>
</html>
