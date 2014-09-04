<div class="box box-solid collapsed-box">
  <div class="box-header">
    <h3 class="box-title">Crear Problema</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
    </div>
  </div>
  <div class="box-body" style="display: none;">
    {{ Form::model($problem, array('url' => URL::route('problems.store'), 'method' => 'POST')) }}
      <div class="form-group">
          <label for="exampleInputEmail1">Pregunta</label>
          {{ Form::text('question', $client->name, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Respuesta</label>
          {{ Form::text('answer', $client->name, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Dificultad</label>
          {{Form::select('dificultad', $dificultad, null, array('class'=>'form-control'))}}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Tipo Pregunta</label>
          {{Form::select('problem_type_id', $problem_type, null, array('class'=>'form-control'))}}
      </div>


      
      <div class="box-footer">
        <button type="submit" class="btn btn-primary">Crear</button>
      </div>
      
    {{ Form::close() }}
  </div><!-- /.box-body -->
</div>