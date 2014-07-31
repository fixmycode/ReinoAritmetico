<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Editar Cliente</h4>
    </div>
    {{ Form::open(array('url' => URL::route('clientss.classrooms.update', [$client->id, $classroom->id]), 'method'=>"PUT")) }}
    <div class="modal-body">
      <label for="nombre">Nombre Cliente</label>
        {{Form::text('name', $classroom->name, ['class' => 'form-control'])}}
        
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      <input type="submit" class="btn btn-primary" name="submit" value="Guardar">
    </div>
    {{ Form::close() }}     
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->