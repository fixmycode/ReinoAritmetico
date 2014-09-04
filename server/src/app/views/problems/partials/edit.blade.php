<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Editar Pregunta</h4>
    </div>
    {{ Form::open(array('url' => URL::route('problems.update', $problem->id), 'method'=>"PUT")) }}
    <div class="modal-body">
    <label for="exampleInputEmail1">Pregunta</label>
          {{ Form::text('question', $problem->question, ['class' => 'form-control']) }}
        <div class="form-group">
            <label for="exampleInputEmail1">Respuesta</label>
            {{ Form::text('answer', $problem->answer, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            <label for="exampleInputEmail1">Dificultad</label>
            {{Form::select('dificultad', $dificultad, null, array('class'=>'form-control'))}}
        </div>

        <div class="form-group">
            <label for="exampleInputEmail1">Tipo Pregunta</label>
            {{Form::select('problem_type_id', $problem_type, null, array('class'=>'form-control'))}}
        </div>

      </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      <input type="submit" class="btn btn-primary" name="submit" value="Guardar">
    </div>
    {{ Form::close() }}     
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->


      
