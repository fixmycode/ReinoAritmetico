@extends('layouts.master')

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
              <th>Pregunta</th>
              <th>Respuesta</th>
              <!-- <th>Tipo Problema</th> -->
              <th>Dificultad</th>

              <th class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($problems as $problem )
            <tr>
                <td>{{ $problem->id}}.</td>
                <td>{{ $problem->problem}}</td>
                <td>{{ $problem->correct_answer}}</td>
                <!-- <td>{{ $problem->problem_type()->first()->type}}</td> -->
                <td>{{ $problem->difficulty}}</td>
                {{--<td><a href="{{ URL::route('problems.index', $problem->id) }}">{{$problem->name}}</a></td> --}}
                <td class="text-right">
                  <a href="#" class="label label-danger destroy" data-problem-id="{{$problem->id}}" title="Seguro?"><i class="fa fa-trash-o"></i></a>
                  <a href="#" class="label label-success" data-toggle="modal" data-target="#theModal" data-remote="{{ URL::route('problems.edit', $problem->id) }}"><i class="fa fa-pencil"></i></a>
                </td> 
            </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div>
  </div>
  <div class="col-md-4 col-md-offset-1">
    @include('problems.partials.create', ['newProblem' => new Problem])
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
      console.log(element.data("problem-id"));
      var problemId = element.data("problem-id");
      var APIurl = '{{ URL::route('problems.index') }}' + '/' + problemId;
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