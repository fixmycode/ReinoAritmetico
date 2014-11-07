@extends('layouts.master')

@section('content')

<div class="row">
<a class="btn" href="{{ URL::previous() }}">Atras</a>
  

</div>
<div class="row">
  <div class="col-md-12">
    <h3>Codigo del Juego: {{$game->uid}}</h3>  
  </div>
</div>
<div class="row">
  

  <div class="col-md-7">
    <div class="box box-success">
      <div id="container"></div>
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