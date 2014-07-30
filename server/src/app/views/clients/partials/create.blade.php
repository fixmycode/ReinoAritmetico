<div class="box box-solid collapsed-box">
  <div class="box-header">
    <h3 class="box-title">Crear Colegio</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
    </div>
  </div>
  <div class="box-body" style="display: none;">
    {{ Form::model($client, array('url' => URL::route('clientss.store'), 'method' => 'POST')) }}
      <div class="form-group">
          <label for="exampleInputEmail1">Nombre</label>
          {{ Form::text('name', $client->name, ['class' => 'form-control']) }}
      </div>
      
      <div class="box-footer">
        <button type="submit" class="btn btn-primary">Crear</button>
      </div>
      
    {{ Form::close() }}
  </div><!-- /.box-body -->
</div>