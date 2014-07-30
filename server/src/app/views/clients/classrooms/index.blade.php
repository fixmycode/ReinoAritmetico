
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')

<div class="row">
  <div class="col-md-6">
    <div class="box box-success">
      <div class="box-body no-padding">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre</th>
              <th style="width: 80px">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($client->classrooms as $index => $classroom )
            <tr>
                <td>{{ $index + 1}}.</td>
                <td><a href="{{ URL::route('clientss.classrooms.show', [$client->id, $classroom->id]) }}">{{$classroom->name}}</a></td>
                <td>
                  <a href="#" class="label label-danger destroy" data-classroom-id="{{$classroom->id}}"><i class="fa fa-trash-o"></i></a>
                  <a href="{{ URL::route('clientss.edit', $classroom->id) }}" class="label label-success"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div>
  </div>
  <div class="col-md-4 col-md-offset-1">
    @include('clients.classrooms.partials.create', ['client' => $client, 'classroom' => new Classroom])
  </div>
</div>
@stop

@section("js")
@parent
<script>
 $(document).ready(function() {
      $(".destroy").on("click",function(){
        var $this = $(this);
        var clientId = $this.data("client-id");
        var APIurl = '{{ URL::route('clientss.index') }}' + '/' + clientId;
        $.ajax({
          url: APIurl,
          type: "DELETE",
          success: function(res) {
            console.log(res);
            $this.closest('tr').remove();
          }
       });
     });
 });
</script>
@stop

@section("js")
@parent
<script>
 $(document).ready(function() {
      $(".destroy").on("click",function(){
        var $this = $(this);
        var clientId = $this.data("classroom-id");
        var APIurl = '{{ URL::route('clientss.classrooms.index', $client->id) }}' + '/' + clientId;
        $.ajax({
          url: APIurl,
          type: "DELETE",
          success: function(res) {
            $this.closest('tr').remove();
          }
       });
     });
 });
</script>
@stop
