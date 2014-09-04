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
              <th>Nombre</th>
              <th class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div>
  </div>
  
</div>
@stop

@section("js")
@parent
{{ HTML::script('js/vendor/bootstrap-confirmation.js') }}

<script>
$(document).ready(function() {
  // $('.destroy').confirmation({
  //   btnCancelLabel: 'Cancelar',
  //   btnOkLabel: 'Eliminar',
  //   onConfirm: function(event, element){
  //     var clientId = element.data("client-id");
  //     var APIurl = '{{ URL::route('clientss.index') }}' + '/' + clientId;
  //     $.ajax({
  //       url: APIurl,
  //       type: "DELETE",
  //       success: function(res) {
  //         console.log(res);
  //         element.closest('tr').remove();
  //       }
  //     });
  //   }
  // });

  $('body').on('hidden.bs.modal', '.modal', function () {
    var $this = $(this);
    $this.removeData('bs.modal');
    $this.find('.modal-title').html('Cargando...');
    $this.find('.modal-body').html('<h1 style="text-align: center;"><i class="fa fa-spinner fa-spin"></i></h1>');
  });
});
</script>
@stop