@extends('layouts.master')

@section('content')

<div class="row">
<a class="btn" href="{{ URL::previous() }}">Atras</a>
  

</div>
<div class="row">
  <div class="col-md-12">
    <h3>Id Grupo: {{$game->id}}</h3>  
  </div>
</div>
<div class="row">
  

  <div class="col-md-7">
    <div class="box box-success">
      <div id="container">
          
      </div>
    </div>
  </div>
  <div class="col-md-5">
      <div class="box box-success">
      <table class="table table-condensed table-hover" id='playersTable'>
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($studentList as $player )
            <tr>
                <td> <a href="{{URL::to('reports/player')}}?player_id={{ $player->id}}">{{ $player->id}}</a></td>
                <td> <a href="{{URL::to('reports/player')}}?player_id={{ $player->id}}">{{ $player->name}}</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>  

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
            text: 'Tags En Usadas en Juego'
        },
        
        xAxis: {
            categories: [
                'Suma',
                'Resta',
                'Multiplicación',
                'División',
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Rainfall (mm)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">({series.name}) Correctas: </td>' +
                '<td style="padding:0"><b> : {point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            @foreach ($studentList as $player )
            {
            name: "{{$player->name}}",
            data: [
            @for ($i = 0; $i < 4; $i++)
                 {{rand ( 0 , 10 )}},
            @endfor
            ]

        },
            @endforeach
        
        ]
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