<div class="box box-solid collapsed-box">
  <div class="box-header">
    <h3 class="box-title">Crear Tag</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
    </div>
  </div>
  <div class="box-body" style="display: none;">

   {{ Form::model($newTag, array('url' => URL::route('tags.store'), 'method' => 'POST' , 'files'=>true)) }}
      <div class="form-group">
          <label for="nombre">Nombre</label>
          {{ Form::text('name', $newTag->name, ['class' => 'form-control']) }}
      </div>



    <div class="box-footer">
        <button type="submit" class="btn btn-primary">Crear</button>
      </div>
    

    {{ Form::close() }}



  </div><!-- /.box-body -->
</div>