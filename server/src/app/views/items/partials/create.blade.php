<div class="box box-solid collapsed-box">
  <div class="box-header">
    <h3 class="box-title">Crear Problema</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
    </div>
  </div>
  <div class="box-body" style="display: none;">
  
    {{ Form::model($newItem, array('url' => URL::route('problems.store'), 'method' => 'POST')) }}
      <div class="form-group">
          <label for="nombre">Nombre</label>
          {{ Form::text('nombre', $newItem->nombre, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="description">Descripcion</label>
          {{ Form::text('description', $newItem->description, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Tipo</label>
          {{Form::select('difficulty', $difficulty, $newItem->difficulty, array('class'=>'form-control'))}}
      </div>
      
    {{--  <div class="box-footer">
        <button type="submit" class="btn btn-primary">Crear</button>
      </div>
      --}}
      
    {{ Form::close() }}


    
  </div><!-- /.box-body -->
</div>