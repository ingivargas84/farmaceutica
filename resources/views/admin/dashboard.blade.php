@extends('admin.layoutadmin')

@section('content')
<style>
    .head {
        padding: 10px;
    }

    table {
        border-radius: 10px;
        padding: 10px;
        width: 100%;
        margin-bottom: 7px;
        color: black;
    }

    #datos, #datos td  {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 16px;
        text-align: center;
    }
</style>
<h1>¡Bienvenido!</h1>
@role('Super-Administrador|Administrador')
{{-- info cards --}}
  <div class="row">
    <div class="col-lg-3 col-xs-6">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fas fa-cash-register"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Ventas Hoy</span>
        <span class="info-box-number">Q. {{$ventas}}</span>
      </div>
    </div>
    </div>

  <div class="col-lg-3 col-xs-6">
  <div class="info-box">
    <span class="info-box-icon bg-yellow"><i class="ion ion-android-cart"></i></span>
    <div class="info-box-content">
      <span class="info-box-text">Compras Hoy</span>
      <span class="info-box-number">Q. {{$compras}}</span>
    </div>
  </div>
  </div>

  <div class="col-lg-3 col-xs-6">
    <div class="info-box">
      <span class="info-box-icon bg-blue"><i class="fas fa-hand-holding-usd"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Abonos Recibidos</span>
        <span class="info-box-number">Q. {{$abonos}}</span>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-xs-6">
  <div class="info-box">
    <span class="info-box-icon bg-purple"><i class="fas fa-file-invoice-dollar"></i></span>
    <div class="info-box-content">
      <span class="info-box-text">Facturaciones</span>
      <span class="info-box-number">Q. {{$facturas}}</span>
    </div>
  </div>
  </div>
</div>

<?php $total = 0;
$c = 0; ?>
@foreach($stock as $w)
<?php
$total += $w->existencias;?>
@endforeach
@foreach($bodegas as $b)
<?php
$c+=1;?>
@endforeach
@if($c > 0)
<div class="row">
  <div class="col-lg-3 col-xs-6">
  <div class="info-box">
    <span class="info-box-icon bg-purple"><i class="fas fa-file-invoice-dollar"></i></span>
    <div class="info-box-content">
      <span class="info-box-text">Inventario</span>
      <span class="info-box-number">Total Productos:
          <?php echo $total; ?>
      </span>
        <a href="#"  id="inventarioVendedor"><span class="info-box-number">Generar PDF</span></a>
    </div>
  </div>
  </div>
</div>
@else
@endif


{{-- sales chart --}}
<div class="row">
  <div class="col-sm-12">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Ventas diarias del mes</h3>
        <div class="box-tools pull-right">
          <h4 style="margin: 0"><span class="label label-success" id="sales_month_label"></span></h4>
        </div>
      </div>
      <div class="box-body">
        <div id="sales-graph" style="height: 175px;"></div>
      </div>
    </div>
  </div>
</div>

{{-- purchase chart --}}
<div class="row">
  <div class="col-sm-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">Compras diarias del mes</h3>
        <div class="box-tools pull-right">
          <h4 style="margin: 0"><span class="label label-warning" id="purchase_month_label"></span></h4>
        </div>
      </div>
      <div class="box-body">
        <div id="purchase-graph" style="height: 175px;"></div>
      </div>
    </div>
  </div>
</div>

{{-- factura chart --}}
<div class="row">
  <div class="col-sm-12">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Facturación diaria del mes</h3>
        <div class="box-tools pull-right">
          <h4 style="margin: 0"><span class="label label-success" id="factura_label"></span></h4>
        </div>
      </div>
      <div class="box-body">
        <div id="factura-graph" style="height: 175px;"></div>
      </div>
    </div>
  </div>
</div>

{{-- abonos chart --}}
<div class="row">
  <div class="col-sm-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Abonos diarios del mes</h3>
        <div class="box-tools pull-right">
          <h4 style="margin: 0"><span class="label label-primary" id="abonos_month_label"></span></h4>
        </div>
      </div>
      <div class="box-body">
        <div id="abonos-graph" style="height: 175px;"></div>
      </div>
    </div>
  </div>
</div>


{{-- Vendedores Cumpleaños--}}
<div class="row">
  <div class="col-sm-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Cumpleañeros</h3>
      </div>
      <div class="box-body">
        <table id="datos">
            <tr>
              <td style="width: 25%;">
                Cliente
              </td>
              <td style="width: 25%">
                Nombre Encargado
              </td>
              <td style="width: 25%">
                Fecha Nacimiento
              </td>
              <td style="width: 25%">
                Teléfono
              </td>
            </tr>
            @foreach($EC as $ec)
            <tr>
              <td>{{$ec->cliente}}</td>
              <td>{{$ec->persona}}</td>
              <td>{{ date("d/m/Y", strtotime($ec->fecha)) }}</td>
              <td>{{$ec->telefono}}</td>
              @endforeach
            </tr>
            @foreach($EP as $ec)
          <tr>
            <td>{{$ec->cliente}}</td>
            <td>{{$ec->persona}}</td>
            <td>{{ date("d/m/Y", strtotime($ec->fecha)) }}</td>
            <td>{{$ec->telefono}}</td>
          </tr>
        @endforeach
        </table>
      </div>
    </div>
  </div>
  {{-- Facturas pendientes de pagos--}}
  <div class="col-sm-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Facturas pendientes de pago</h3>
      </div>
      <div class="box-body">
        <table id="datos">
            <tr>
              <td style="width: 25%;">
                Factura No.
              </td>
              <td style="width: 25%">
                Proveedor
              </td>
              <td style="width: 25%">
                Monto
              </td>
              <td style="width: 25%">
                Días restantes para pagar
              </td>
            </tr>
            @foreach($FPP as $f)
            <tr>
              <td>{{$f->factura}}</td>
              <td>{{$f->proveedor}}</td>
              <td>{{$f->monto }}</td>
              <td>{{$f->dias_restantes}}</td>
              @endforeach
            </tr>
        </table>
      </div>
    </div>
  </div>
</div>


<script defer>

$(document).ready(function(){
  var date = new Date();
  var month = date.toLocaleDateString('es-ES', {month: 'long'});
  var sales_url = "{{ route('dashboard.salesData') }}";
  var purchase_url = "{{ route('dashboard.purchaseData') }}";
  var factura_url = "{{ route('dashboard.facturaData') }}";
  var abonos_url = "{{ route('dashboard.abonoData') }}";

  $('#sales_month_label').text(month);
  $('#purchase_month_label').text(month);
  $('#abonos_month_label').text(month);

$.ajax({
  type: "GET",
  headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
  url: sales_url,
  dataType: 'json',
  success: function(data){
    for (let i = 0; i < data.length; i++) {
      var newDate = new Date(data[i].date.replace(/-/g, '\/'));
      data[i].date = newDate.toLocaleDateString('es-ES', {day: 'numeric', month: 'long'});
    }

    for (let i = 0; i < data.length; i++) {
      data[i].amount = parseInt(data[i].amount).toFixed(2);
    }

    new Morris.Bar({
      element: 'sales-graph',
      data: data,
      xkey: 'date',
      ykeys: ['amount'],
      labels: ['Vendido'],
      resize: true,
      preUnits: 'Q. ',
      barColors: ['#00a65a'],
      hideHover: true,
    });
  },
  error: function(){
    console.log('error al obtener datos de ventas');
  }
});

$.ajax({
  type: "GET",
  headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
  url: purchase_url,
  dataType: 'json',
  success: function(data){

    for (let i = 0; i < data.length; i++) {
      var newDate = new Date(data[i].date.replace(/-/g, '\/'));
      data[i].date = newDate.toLocaleDateString('es-ES', {day: 'numeric', month: 'long'});
    }

    for (let i = 0; i < data.length; i++) {
      data[i].amount = parseFloat(data[i].amount).toFixed(2);
    }

    new Morris.Bar({
      element: 'purchase-graph',
      data: data,
      xkey: 'date',
      ykeys: ['amount'],
      labels: ['Comprado'],
      resize: true,
      preUnits: 'Q. ',
      barColors: ['#f39c12'],
      hideHover: true,
    });
  },
  error: function(){
    console.log('error al obtener datos de compras');
  }
});

$.ajax({
  type: "GET",
  headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
  url: factura_url,
  dataType: 'json',
  success: function(data){

    for (let i = 0; i < data.length; i++) {
      var newDate = new Date(data[i].date.replace(/-/g, '\/'));
      data[i].date = newDate.toLocaleDateString('es-ES', {day: 'numeric', month: 'long'});
    }

    for (let i = 0; i < data.length; i++) {
      data[i].amount = parseFloat(data[i].amount).toFixed(2);
    }

    new Morris.Bar({
      element: 'factura-graph',
      data: data,
      xkey: 'date',
      ykeys: ['amount'],
      labels: ['Facturado'],
      resize: true,
      preUnits: 'Q. ',
      barColors: ['#3382FF'],
      hideHover: true,
    });
  },
  error: function(){
    console.log('error al obtener datos de facturación');
  }
});

$.ajax({
  type: "GET",
  headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
  url: abonos_url,
  dataType: 'json',
  success: function(data){
    for (let i = 0; i < data.length; i++) {
      var newDate = new Date(data[i].date.replace(/-/g, '\/'));
      data[i].date = newDate.toLocaleDateString('es-ES', {day: 'numeric', month: 'long'});
    }

    for (let i = 0; i < data.length; i++) {
      data[i].amount = parseInt(data[i].amount).toFixed(2);
    }

    new Morris.Bar({
      element: 'abonos-graph',
      data: data,
      xkey: 'date',
      ykeys: ['amount'],
      labels: ['Abonos recibidos'],
      resize: true,
      preUnits: 'Q. ',
      barColors: ['#0073b7'],
      hideHover: true,
    });
  },
  error: function(){
    console.log('error al obtener datos de abonos');
  }
});

});

</script>

@endrole

@role('Vendedor')

{{-- info cards --}}
  <div class="row">
  <div class="col-lg-4 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-yellow"><i class="fas fa-door-open"></i></span>
    <div class="info-box-content">
      <span class="info-box-text">Visitas Hechas Hoy</span>
      <span class="info-box-number">{{$visitas}}</span>
    </div>
  </div>
  </div>

    <div class="col-lg-4 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fas fa-cash-register"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Ventas Hoy</span>
        <span class="info-box-number">Q. {{$ventas}}</span>
      </div>
    </div>
    </div>

  <div class="col-lg-4 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-blue"><i class="fas fa-hand-holding-usd"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Abonos Recibidos Hoy</span>
        <span class="info-box-number">Q. {{$abonos}}</span>
      </div>
    </div>
  </div>

</div>

<div class="row">
  <div class="col-lg-3 col-xs-6">
  <div class="info-box">
    <span class="info-box-icon bg-purple"><i class="fas fa-file-invoice-dollar"></i></span>
    <div class="info-box-content">
      <span class="info-box-text">Inventario</span>
      <?php $total = 0; ?>
      <span class="info-box-number">Total Productos:
        @foreach($stock as $w)
        <?php
        $total += $w->existencias;?>
        @endforeach
          <?php echo $total; ?>
      </span>
        <a href="#"  id="inventarioVendedor"><span class="info-box-number">Generar PDF</span></a>


    </div>
  </div>
  </div>

</div>

{{-- sales chart --}}
<div class="row">
  <div class="col-sm-12">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Ventas diarias del mes</h3>
        <div class="box-tools pull-right">
          <h4 style="margin: 0"><span class="label label-success" id="sales_month_label"></span></h4>
        </div>
      </div>
      <div class="box-body">
        <div id="sales-graph" style="height: 175px;"></div>
      </div>
    </div>
  </div>
</div>

{{-- abonos chart --}}
<div class="row">
  <div class="col-sm-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Abonos diarios del mes</h3>
        <div class="box-tools pull-right">
          <h4 style="margin: 0"><span class="label label-primary" id="abonos_month_label"></span></h4>
        </div>
      </div>
      <div class="box-body">
        <div id="abonos-graph" style="height: 175px;"></div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Cumpleañeros</h3>
      </div>
      <div class="box-body">
        <table id="datos">
            <tr>
              <td style="width: 25%;">
                Cliente
              </td>
              <td style="width: 25%">
                Nombre Encargado
              </td>
              <td style="width: 25%">
                Fecha Nacimiento
              </td>
              <td style="width: 25%">
                Teléfono
              </td>
            </tr>
            @foreach($EC as $ec)
            <tr>
              <td>{{$ec->cliente}}</td>
              <td>{{$ec->persona}}</td>
              <td>{{ date("d/m/Y", strtotime($ec->fecha)) }}</td>
              <td>{{$ec->telefono}}</td>
              @endforeach
            </tr>
            @foreach($EP as $ec)
          <tr>
            <td>{{$ec->cliente}}</td>
            <td>{{$ec->persona}}</td>
            <td>{{ date("d/m/Y", strtotime($ec->fecha)) }}</td>
            <td>{{$ec->telefono}}</td>
            @endforeach
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<script>

$(document).ready(function(){
  var date = new Date();
  var month = date.toLocaleDateString('es-ES', {month: 'long'});
  var sales_url = "{{ route('dashboard.salesData') }}";
  var abonos_url = "{{ route('dashboard.abonoData') }}";

  $('#sales_month_label').text(month);
  $('#abonos_month_label').text(month);

$.ajax({
  type: "GET",
  headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
  url: sales_url,
  dataType: 'json',
  success: function(data){
    for (let i = 0; i < data.length; i++) {
      var newDate = new Date(data[i].date.replace(/-/g, '\/'));
      data[i].date = newDate.toLocaleDateString('es-ES', {day: 'numeric', month: 'long'});
    }

    for (let i = 0; i < data.length; i++) {
      data[i].amount = parseInt(data[i].amount).toFixed(2);
    }

    new Morris.Bar({
      element: 'sales-graph',
      data: data,
      xkey: 'date',
      ykeys: ['amount'],
      labels: ['Vendido'],
      resize: true,
      preUnits: 'Q. ',
      barColors: ['#00a65a'],
      hideHover: true,
    });
  },
  error: function(){
    console.log('error al obtener datos de ventas');
  }
});

$.ajax({
  type: "GET",
  headers: { 'X-CSRF-TOKEN': $('#tokenReset').val() },
  url: abonos_url,
  dataType: 'json',
  success: function(data){
    for (let i = 0; i < data.length; i++) {
      var newDate = new Date(data[i].date.replace(/-/g, '\/'));
      data[i].date = newDate.toLocaleDateString('es-ES', {day: 'numeric', month: 'long'});
    }

    for (let i = 0; i < data.length; i++) {
      data[i].amount = parseInt(data[i].amount).toFixed(2);
    }

    new Morris.Bar({
      element: 'abonos-graph',
      data: data,
      xkey: 'date',
      ykeys: ['amount'],
      labels: ['Abonos recibidos'],
      resize: true,
      preUnits: 'Q. ',
      barColors: ['#0073b7'],
      hideHover: true,
    });
  },
  error: function(){
    console.log('error al obtener datos de abonos');
  }
});

});


</script>

@endrole

@stop
