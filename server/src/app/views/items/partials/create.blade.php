<div class="box box-solid collapsed-box">
  <div class="box-header">
    <h3 class="box-title">Crear Ítem</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
    </div>
  </div>
  <div class="box-body" style="display: none;">

   {{ Form::model($newItem, array('url' => URL::route('items.store'), 'method' => 'POST' , 'files'=>true)) }}
      <div class="form-group">
          <label for="nombre">Nombre</label>
          {{ Form::text('nombre', $newItem->nombre, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="description">Descripcion</label>
          {{ Form::text('description', $newItem->description, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="description">Precio</label>
          {{ Form::text('price', $newItem->price, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Tipo</label>
          {{Form::select('item_type', $item_type, $newItem->type, array('class'=>'form-control'))}}
      </div>

      <div class="form-group">
        {{ Form::file('image') }}
      </div>
      <div class="form-group">
          <label for="description">head.x</label>
          {{ Form::text('headX', $newItem->headX, ['class' => 'form-control']) }}
      </div>
      <div class="form-group">
          <label for="description">head.y</label>
          {{ Form::text('headY', $newItem->headY, ['class' => 'form-control']) }}
      </div>
      <div class="form-group">
          <label for="description">hand.x</label>
          {{ Form::text('handX', $newItem->handX, ['class' => 'form-control']) }}
      </div>
      <div class="form-group">
          <label for="description">hand.y</label>
          {{ Form::text('handY', $newItem->handY, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Clase</label>
          {{Form::select('character_type', $character_type, $newItem->character_type, array('class'=>'form-control'))}}
      </div>

    <div class="box-footer">
        <button type="submit" class="btn btn-primary">Crear</button>
      </div>


    {{ Form::close() }}



  </div><!-- /.box-body -->
</div>