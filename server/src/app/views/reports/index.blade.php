@extends('layouts.master')

@section('content')


<div class="row">

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#curso" role="tab" data-toggle="tab">Curso</a></li>
  <li role="presentation"><a href="#alumno" role="tab" data-toggle="tab">Alumno</a></li>
  <!-- <li role="presentation"><a href="#grupal" role="tab" data-toggle="tab">Grupal</a></li> -->
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="curso">
    <div class="col-md-7">
      <h3>Cantidad de Juegos: {{$numberOfGames}}</h3>
      <div class="box box-success">
        <div id="container"></div>
      </div>
    </div>
    <div class="col-md-5">


      <table class="table table-condensed table-hover" id='successTable'>
        <thead>
          <tr>
            <th>Tipo</th>
            <th>Correctas</th>
            <th>Incorrectas</th>
            <th>Tasa de Exito</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($successRate as $type )
          <tr>
              <td> {{$type->tag}}</td>
              <td> {{$type->correct}}</td>
              <td> {{$type->wrong}}</td>
              <td> {{$type->success_rate}} %</td>

          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane" id="alumno">
  <div class="col-md-7">
    <table class="table table-condensed table-hover" id='playersTable'>
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($players as $player )
            <tr>
                <td> <a href="{{URL::to('reports/player')}}?player_id={{ $player->id}}">{{ $player->id}}</a></td>
                <td> <a href="{{URL::to('reports/player')}}?player_id={{ $player->id}}">{{ $player->name}}</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
  </div>

    </div>

  <div role="tabpanel" class="tab-pane" id="grupal">
    <div class="col-md-7">
      <table class="table table-condensed table-hover" id='groupsTable'>
        <thead>
          <tr>
            <th style="width: 10px">#</th>
            <th>UID</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($groups as $game )
          <tr>
              <td> <a href="{{URL::to('reports/group')}}?gameId={{ $game->id }} ">{{$game->id}}</a> </td>
              <td> <a href="{{URL::to('reports/group')}}?gameId={{ $game->id }} ">{{$game->uid}}</a> </td>

          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

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
          title: { text: 'Tag' },
          categories: {{$averageCategories}},
          labels: {
                enabled: false
            },
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
            headerFormat: '<span style="font-size:10px"></span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}  </td>' +
                '<td style="padding:0"><b> : {point.y} [sec]</b></td></tr>',
            footerFormat: '</table>'
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