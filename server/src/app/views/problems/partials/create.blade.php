<div class="box box-solid collapsed-box">
  <div class="box-header">
    <h3 class="box-title">Crear Problema</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
    </div>
  </div>
  <div class="box-body" style="display: none;">
    {{ Form::model($newProblem, array('url' => URL::route('problems.store'), 'method' => 'POST')) }}
      <div class="form-group">
          <label for="exampleInputEmail1">Pregunta</label>
          {{ Form::text('problem', $newProblem->problem, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Respuesta</label>
          {{ Form::text('correct_answer', $newProblem->correct_answer, ['class' => 'form-control']) }}
      </div>

      <div class="form-group">
          <label for="exampleInputEmail1">Dificultad</label>
          {{Form::select('difficulty', $difficulty, $newProblem->difficulty, array('class'=>'form-control'))}}
      </div>

      <div class="form-group">
        <select data-placeholder="Seleccione Tags" style="width: 350px" multiple="" class="tagSelect" name="tags[]">          
          @foreach ($tags as $tag)
            <option value="{{$tag->id}}">{{$tag->name}}</option>
          @endforeach
        </select>
      </div>
          
      
      <div class="box-footer">
        <button type="submit" class="btn btn-primary">Crear</button>
      </div>


      
    {{ Form::close() }}


    
  </div><!-- /.box-body -->
</div>