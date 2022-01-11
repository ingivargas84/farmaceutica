<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
    @page {
            margin-top: 4rem;

        }

      .titulo {
          font-size: 12px;
      }
      .about{
        font-size: 11px;
      }
      table tr{
        height: 1.5px;
      }
      .fine{
        font-size: 8;
      }
      .table{
        margin-top: 4rem;
        margin-left: 4rem;
        margin-right: 0rem;
      }
      table{
        width: 100%;
        margin-right: 0rem;
      }
      br{
        height: 0.8px;
      }
      body {
        margin-top:  3rem;

        margin-right: -1.5rem;

      }

      .body {
            margin-left: 0.8rem;
      }
      .body1 {
            margin-left: 0.8rem;
            margin-right: -2rem;
      }
      footer{
        bottom: 20.5rem;
        position: absolute;
        margin-right: -1.5rem;
      }
    </style>
  </head>
  <body>
    <table class="body">
      <tr>

        @foreach($encabezado as $e)
        <td style="font-size:12px; width:20%; align:left;">{{$e->fecha_factura}}</td>
        <td style="font-size:12px;  width:55%; align:right;"></td>
        <td style="font-size:12px;  width:20%; align:right;">{{$e->dias_credito}} días</td>

        <td style="font-size:10px;  width:15%; text-align:right;">{{$e->nit}}</td>
        @endforeach
      </tr>
    </table>
    <table class="body">
      <tr>
        @foreach($encabezado as $e)
        <td style="font-size:12px; align:left; width:90%;">{{$e->nombre_cliente}}</td>
        <td style="font-size:10px; text-align:right; width:10%;">{{$e->vendedor}}</td>
        @endforeach
      </tr>
    </table >
    <table class="body">
         @foreach($encabezado as $e)
         <td style="font-size:12px; align:left; width:90%;">{{$e->direccion}}</td>
         <td style="font-size:12px; text-align:right; width:10%;">{{$e->telefono_compras}}</td>
         @endforeach
        </tr>
    </table>
    <br>
    <table class="body1">
      @foreach($detalles as $d)
      <tr>
        <td style="width:15%; font-size:10px;"><b>{{$d->cantidad}}</b></td>
        <td style="width:50%; font-size:10px;"><b>{{$d->producto}}</b></td>
        <td style="width:15%; font-size:10px;  text-align:right;"><b>Q.<?php echo number_format($d->precio, 2, ".", ",");?></b></td>
        <td style="width:15%; font-size:10px;  text-align:right;"><b>Q.<?php echo number_format($d->subtotal, 2, ".", ",");?></b></td>
        <td style="width:5%; font-size:10px;"></td>
      </tr>
      @endforeach
    </table>
    <footer>
      <table>
        <tr>
          <td style="width:10%;"></td>
          <td style="width:80%; font-size:10px; text-align:left;">{{$ver}}</td>
          @foreach($encabezado as $e)
          <td style="width:10%; font-size:12px; text-align:right;"><?php echo number_format($e->total, 2, ".", ",");?></td>
            @endforeach
          
        </tr>
      </table>
    </footer>

  </body>



</html>