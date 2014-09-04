@extends('layouts.master')

@section('content')

<div class="row">
  <div class="col-md-8">
    <div class="box box-success">
      <div class="box-body no-padding">
        <table class="table table-condensed table-hover">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre</th>
              <th>Android UID</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($classroom->players as $index => $player )
            <tr>
                <td>{{ $index + 1}}.</td>
                <td>{{$player->name}}</td>
                <td>{{$player->android_id}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div>
  </div>
</div>
@stop