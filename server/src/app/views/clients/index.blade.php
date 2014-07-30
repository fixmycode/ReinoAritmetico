
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
            @foreach ($clients as $index => $client )
            <tr>
                <td>{{ $index + 1}}.</td>
                <td><a href="{{ URL::route('clientss.classrooms.index', $client->id) }}">{{$client->name}}</a></td>
                <td>
                  <a href="#" class="label label-danger destroy" data-client-id="{{$client->id}}"><i class="fa fa-trash-o"></i></a>
                  <a href="{{ URL::route('clientss.edit', $client->id) }}" class="label label-success"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div>
  </div>
  <div class="col-md-4 col-md-offset-1">
    @include('clients.partials.create', ['client' => new Client])
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