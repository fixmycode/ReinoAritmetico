<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Editar Pregunta</h4>
    </div>


    {{ Form::open(array('url' => URL::route('items.update', $item->id), 'method' => 'PUT', 'files'=>true)) }}
    <div class="modal-body">
      <div class="form-group">
          <label for="nombre">Nombre</label>
          {{ Form::text('nombre', $item->nombre, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="description">Descripcion</label>
          {{ Form::text('description', $item->description, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="description">Precio</label>
          {{ Form::text('price', $item->price, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Tipo</label>
          {{Form::select('item_type', $item_type, $item->type, array('class'=>'form-control'))}}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Clase</label>
          {{Form::select('character_type', $character_type, $item->character_type, array('class'=>'form-control'))}}
      </div>

      <div class="form-group">
          <label for="description">head.x</label>
          {{ Form::text('headX', $item->headX, ['class' => 'form-control']) }}
      </div>
      <div class="form-group">
          <label for="description">head.y</label>
          {{ Form::text('headY', $item->headY, ['class' => 'form-control']) }}
      </div>
      <div class="form-group">
          <label for="description">hand.x</label>
          {{ Form::text('handX', $item->handX, ['class' => 'form-control']) }}
      </div>
      <div class="form-group">
          <label for="description">hand.y</label>
          {{ Form::text('handY', $item->handY, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
        {{ Form::file('image') }}
      </div>
    </div>


      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Actualizar</button>
      </div>


    {{ Form::close() }}
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->



