<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Editar Tag</h4>
    </div>
    

    {{ Form::open(array('url' => URL::route('tags.update', $tag->id), 'method' => 'PUT')) }}
      <div class="form-group">
          <label for="nombre">Nombre</label>
          {{ Form::text('name', $tag->name, ['class' => 'form-control']) }}
      </div>


      
      
      <div class="box-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Actualizar</button>
      </div>
      
      
    {{ Form::close() }}   
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->


      
