
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')

@include('partials.theModal')

<div class="row">
  <div class="col-md-6">
    <div class="box box-success">
      <div class="box-body no-padding">
        <table class="table table-condensed table-hover">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre</th>
              <th class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($client->classrooms as $index => $classroom )
            <tr>
                <td>{{ $index + 1}}.</td>
                <td>{{$classroom->name}}</td>
                <td class="text-right">
                  <a href="#" class="label label-danger destroy" data-classroom-id="{{$classroom->id}}" title="¿Seguro?"><i class="fa fa-trash-o"></i></a>
                  <a href="#" class="label label-success" data-toggle="modal" data-target="#theModal" data-remote="{{ URL::route('clientss.classrooms.edit', [$client->id, $classroom->id]) }}"><i class="fa fa-pencil"></i></a>
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
{{ HTML::script('js/vendor/bootstrap-confirmation.js') }}
<script>
$(document).ready(function() {
  $('.destroy').confirmation({
    btnCancelLabel: 'Cancelar',
    btnOkLabel: 'Eliminar',
    onConfirm: function(event, element){
      var classroomId = element.data("classroom-id");
      var APIurl = '{{ URL::route('clientss.classrooms.index', $client->id) }}' + '/' + classroomId;
      $.ajax({
        url: APIurl,
        type: "DELETE",
        success: function(res) {
          console.log(res);
          element.closest('tr').remove();
        }
      });
    }
  });

  $('body').on('hidden.bs.modal', '.modal', function () {
    var $this = $(this);
    $this.removeData('bs.modal');
    $this.find('.modal-title').html('Cargando...');
    $this.find('.modal-body').html('<h1 style="text-align: center;"><i class="fa fa-spinner fa-spin"></i></h1>');
  });
});
</script>
@stop
