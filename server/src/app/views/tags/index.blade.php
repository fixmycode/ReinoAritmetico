@extends('layouts.master')

@section('content')

@include('partials.theModal')

<div class="row">
  <div class="col-md-7">
    <div class="box box-success">
      <div class="box-body no-padding">
        <table class="table table-condensed table-hover" id='tagsTable'>
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre</th>

              <th class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($tags as $tag )
            <tr>
                <td>{{ $tag->id}}</td>
                <td> {{$tag->name }}</td>

                <td class="text-right">
                  <a href="#" class="label label-danger destroy" data-tag-id="{{$tag->id}}" title="Seguro?"><i class="fa fa-trash-o"></i></a>
                  <a href="#" class="label label-success" data-toggle="modal" data-target="#theModal" data-remote="{{ URL::route('tags.edit', $tag->id) }}"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div>
  </div>
  <div class="col-md-3 col-md-offset-1">
    @include('tags.partials.create', ['newTag' => new Tag])
  </div>

</div>
@stop

@section("js")
@parent
{{ HTML::script('js/vendor/bootstrap-confirmation.js') }}
<script src="//cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script>
$(document).ready(function() {

  $('#tagsTable').dataTable({
        "bFilter": false,
        "bInfo": false,
        "filter": true,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por pagina",
            "zeroRecords": "Nothing found - sorry",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "paginate": {
                  "previous": "Anterior",
                  "next": "Siguiente"
                }
        },
        "fnPreDrawCallback": function( oSettings ) {
          $('.destroy').confirmation({
            btnCancelLabel: 'Cancelar',
            btnOkLabel: 'Eliminar',
            onConfirm: function(event, element){
              console.log(element.data("tag-id"));
              var tagId = element.data("tag-id");
              var APIurl = '{{ URL::route('tags.index') }}' + '/' + tagId;
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
        }
  });

  $('.dataTables_paginate').parent().prev().remove();
  $('.dataTables_paginate').parent().removeClass('col-xs-6').addClass('col-xs-12');



  $('body').on('hidden.bs.modal', '.modal', function () {
    var $this = $(this);
    $this.removeData('bs.modal');
    $this.find('.modal-title').html('Cargando...');
    $this.find('.modal-body').html('<h1 style="text-align: center;"><i class="fa fa-spinner fa-spin"></i></h1>');
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