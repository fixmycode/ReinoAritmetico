@extends('layouts.master')

@section('content')


<div class="row">

  <div class="col-md-7">
  <h3>Alumno: {{$player->name}}</h3>
    <div class="box box-success">
      <div id="container"></div>
    </div>
  </div>
  
  

</div>
@stop

@section("js")
@parent
{{ HTML::script('js/vendor/bootstrap-confirmation.js') }}
{{ HTML::script('js/highcharts/js/highcharts.js') }}



<script src="//cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src=""></script>
<script>
$(document).ready(function() {
 $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Promedio Tiempo Respuesta'
        },
        xAxis: {
          categories: {{$averageCategories}}
        },
        subtitle: {
            text: 'Por Tipo de Problema'
        },
        
        yAxis: {
            min: 0,
            title: {
                text: 'Tiempo (sec)'
            }
        },
        tooltip: {
            
        },
        plotOptions: {
            
        },
        series: {{$averageData}}
    });
  
});
</script>
@stop

@section('css')
@parent

<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.css">
<style>
  .dataTables_length {
    padding: 10px 10px 0 10px;
  }
  div.dataTables_paginate { float: none; margin-left: 10px;}
</style>
@stop